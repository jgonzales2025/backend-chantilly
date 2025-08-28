<?php

namespace App\Http\Controllers\Payment;

use App\Enum\PaymentStatusEnum;
use App\Enum\TransactionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Customer;
use App\Models\NiubizTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderPaid;
use App\Services\NiubizService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $niubiz;
    protected $smsService;

    public function __construct(NiubizService $niubiz, SmsService $smsService)
    {
        $this->niubiz = $niubiz;
        $this->smsService = $smsService;
    }

    /**
     * Obtener configuración para el frontend
     */
    public function getConfig()
    {
        $env = config('niubiz.pro') ? 'prod' : 'sandbox';
        
        return response()->json([
            'success' => true,
            'data' => [
                'merchant_id' => config('niubiz.merchant_id'),
                'checkout_js_url' => config("niubiz.urls.checkout_js.{$env}"),
                'environment' => $env,
                'merchant_logo' => 'http://localhost:8000/storage/logo/logocheckout.png'
            ]
        ]);
    }

    /**
     * Obtener session para el frontend (checkout.js)
     * Actualizado con BD y validaciones mejoradas
     */
    public function getSession(StoreOrderRequest $req)
    {
        $validatedOrderData = $req->validated();

        Log::info("Datos enviados desde el frontend", $validatedOrderData);

        DB::beginTransaction();
        
        try {
            // Generar purchaseNumber si no se proporciona
            $lastTransaction = NiubizTransaction::orderBy('id', 'desc')->first();
            $nextNumber = $lastTransaction ? $lastTransaction->id + 1 : 1;
            $validatedOrderData['purchaseNumber'] = (string) $nextNumber;
           
            $customer = Customer::find($validatedOrderData['customer_id']);

            if ($customer && $customer->created_at) {
                $diasRegistrado = now()->diffInDays($customer->created_at);
            }
            // Crear sesión con Niubiz (esto creará el registro en niubiz_transactions)
            $result = $this->niubiz->createSession(
                $validatedOrderData['total_amount'],
                $customer,
                $validatedOrderData['purchaseNumber'],
                $diasRegistrado
            );

            DB::commit();
            $merchant_logo = 'http://192.168.18.28:8000/storage/logo/logocheckout.png';
            Log::info('Sesión de pago iniciada', [
                'purchase_number' => $validatedOrderData['purchaseNumber'],
                'order_id' => $validatedOrderData['order_id'] ?? null,
                'amount' => $validatedOrderData['total_amount'],
                'merchant_logo' => $merchant_logo
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'sessionToken' => $result['sessionKey'] ?? null,
                    'purchase_number' => $validatedOrderData['purchaseNumber'],
                    'merchant_id' => config('niubiz.merchant_id'),
                    'amount' => $validatedOrderData['total_amount'],
                    'merchant_logo' => 'https://chantilly-app-px74f.ondigitalocean.app/storage/logo/logocheckout.png',
                    'order_data' => $validatedOrderData
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error obteniendo sesión de pago', [
                'error' => $e->getMessage(),
                'request' => $validatedOrderData
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al inicializar el pago',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Procesar pago (backend)
     * Actualizado con manejo completo de BD
     */
    public function pay(Request $request)
    {
        $validated = $request->validate([
            'tokenId' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'purchaseNumber' => 'required|string|exists:niubiz_transactions,purchase_number',
            'order_data' => 'required|array'
        ]);
        Log::info("Datos recibidos para procesar pago", $validated);
        DB::beginTransaction();
        
        try {
            $validatedOrderData = $validated['order_data'];
            $order = $this->createOrder($validatedOrderData);
            // Verificar que la transacción existe y está pendiente
            $transaction = NiubizTransaction::where('purchase_number', $validated['purchaseNumber'])->first();
            $transaction->update(['order_id' => $order->id]);
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transacción no encontrada'
                ], 404);
            }

            if ($transaction->status !== TransactionStatusEnum::PENDING) {
                return response()->json([
                    'success' => false,
                    'message' => 'La transacción ya fue procesada',
                    'status' => $transaction->status->value
                ], 400);
            }

            // Procesar pago con Niubiz
            $result = $this->niubiz->processPayment(
                $validated['tokenId'],
                $validated['amount'],
                $validated['purchaseNumber']
            );

            // Verificar resultado del pago
            $actionCode = $result['dataMap']['ACTION_CODE'] ?? null;
            $isSuccess = $actionCode === '000';

            // Actualizar orden si existe
            if ($transaction->order) {
                if ($isSuccess) {
                    $this->sendPaymentNotifications($transaction->order, $transaction);
                } else {
                    $transaction->order->update(['payment_status' => PaymentStatusEnum::FAILED]);
                }
            }

            DB::commit();

            // Preparar respuesta
            $responseData = [
                'success' => $isSuccess,
                'data' => [
                    'transaction_id' => $result['dataMap']['TRANSACTION_ID'] ?? null,
                    'action_code' => $actionCode,
                    'action_description' => $result['dataMap']['ACTION_DESCRIPTION'] ?? null,
                    'transaction_date' => $result['dataMap']['TRANSACTION_DATE'] ?? null,
                    'amount' => $result['order']['amount'] ?? null,
                    'currency' => $result['order']['currency'] ?? 'PEN',
                    'purchase_number' => $validated['purchaseNumber'],
                    'brand' => $result['dataMap']['BRAND'],
                    'card' => $result['dataMap']['CARD']
                ],
                'message' => $isSuccess ? 'Pago procesado exitosamente' : 'Pago rechazado'
            ];

            Log::info('Pago procesado', [
                'purchase_number' => $validated['purchaseNumber'],
                'success' => $isSuccess,
                'action_code' => $actionCode,
                'transaction_id' => $result['dataMap']['TRANSACTION_ID'] ?? null
            ]);

            return response()->json($responseData, $isSuccess ? 200 : 400);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error procesando pago', [
                'error' => $e->getMessage(),
                'request' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    public function payResponse(Request $request)
    {
        // Los datos de la transacción llegan por POST
        $data = $request->all();
        Log::info('Respuesta de Niubiz recibida', $data);

        $transactionToken = $data['transactionToken'] ?? null;
        $customerEmail = $data['customerEmail'] ?? null;
        $channel = $data['channel'] ?? null;

        // BUSCAR el purchaseNumber en la base de datos usando el transactionToken
        // O usar la sesión más reciente pendiente
        $transaction = NiubizTransaction::where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->first();

        $purchaseNumber = $transaction ? $transaction->purchase_number : null;
        $amount = $transaction ? $transaction->amount : 117.88;

        // Construir URL de redirección
        $redirectUrl = 'http://localhost:8080/niubiz/payment-result.html?' . http_build_query([
            'tokenId' => $transactionToken,
            'purchaseNumber' => $purchaseNumber, // Ahora tenemos el purchaseNumber correcto
            'customerEmail' => $customerEmail,
            'channel' => $channel,
            'amount' => $amount,
            'currency' => 'S/',
            'autoProcess' => 'true'
        ]);

        return redirect($redirectUrl);
    }

    /**
     * Consultar estado de una transacción
     */
    public function getTransactionStatus($purchaseNumber)
    {
        try {
            $transaction = NiubizTransaction::with('order')
                ->where('purchase_number', $purchaseNumber)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transacción no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'purchase_number' => $transaction->purchase_number,
                    'status' => $transaction->status->value,
                    'status_label' => $transaction->status->label(),
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'transaction_id' => $transaction->transaction_id,
                    'action_code' => $transaction->action_code,
                    'error_message' => $transaction->error_message,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                    'order' => $transaction->order ? [
                        'id' => $transaction->order->id,
                        'payment_status' => $transaction->order->payment_status?->value,
                        'paid_at' => $transaction->order->paid_at
                    ] : null,
                    'niubiz_response' => $transaction->getNiubizResponseFormatted()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error consultando estado de transacción', [
                'purchase_number' => $purchaseNumber,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al consultar el estado',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Listar transacciones con filtros
     */
    public function getTransactions(Request $request)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,success,failed,cancelled',
            'order_id' => 'sometimes|exists:orders,id',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1'
        ]);

        try {
            $query = NiubizTransaction::with('order')
                ->orderBy('created_at', 'desc');

            // Aplicar filtros
            if (isset($validated['status'])) {
                $status = TransactionStatusEnum::from($validated['status']);
                $query->where('status', $status);
            }

            if (isset($validated['order_id'])) {
                $query->where('order_id', $validated['order_id']);
            }

            // Paginación
            $perPage = $validated['per_page'] ?? 15;
            $transactions = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $transactions->items(),
                'meta' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error listando transacciones', [
                'error' => $e->getMessage(),
                'filters' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener transacciones',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de transacciones
     */
    public function getStats(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from'
        ]);

        try {
            $query = NiubizTransaction::query();

            // Aplicar filtros de fecha
            if (isset($validated['date_from'])) {
                $query->whereDate('created_at', '>=', $validated['date_from']);
            }

            if (isset($validated['date_to'])) {
                $query->whereDate('created_at', '<=', $validated['date_to']);
            }

            // Obtener estadísticas
            $stats = [
                'total_transactions' => $query->count(),
                'successful_transactions' => $query->clone()->where('status', TransactionStatusEnum::SUCCESS)->count(),
                'failed_transactions' => $query->clone()->where('status', TransactionStatusEnum::FAILED)->count(),
                'pending_transactions' => $query->clone()->where('status', TransactionStatusEnum::PENDING)->count(),
                'total_amount' => $query->clone()->where('status', TransactionStatusEnum::SUCCESS)->sum('amount'),
                'average_amount' => $query->clone()->where('status', TransactionStatusEnum::SUCCESS)->avg('amount'),
                'success_rate' => 0
            ];

            // Calcular tasa de éxito
            if ($stats['total_transactions'] > 0) {
                $stats['success_rate'] = round(
                    ($stats['successful_transactions'] / $stats['total_transactions']) * 100, 
                    2
                );
            }

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo estadísticas', [
                'error' => $e->getMessage(),
                'filters' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Enviar notificaciones de pago exitoso
     */
    private function sendPaymentNotifications($order, $transaction)
    {
        $order->load([
            'customer', 
            'items.product', 
            'items.productVariant.product',
            'local'
        ]);

        $customer = $order->customer;
        
        if (!$customer) {
            return;
        }

        // Enviar correo
        try {
            if ($customer->email) {
                $customer->notify(new OrderPaid($order, $transaction));
                Log::info('Correo de confirmación enviado', [
                    'customer_email' => $customer->email,
                    'order_id' => $order->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error enviando correo de confirmación', [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);
        }

        // Enviar SMS
        try {
            if ($customer->phone) {
                $phone = "+51" . ltrim($customer->phone, '0');
                $orderNumber = $order->order_number;
                $deliveryDate = $order->delivery_date;
                $total = number_format($order->total, 2);

                $smsSent = $this->smsService->sendPaymentConfirmation($phone, $orderNumber, $total, $deliveryDate);

                if ($smsSent) {
                    Log::info('SMS de confirmación enviado', [
                        'customer_phone' => $customer->phone,
                        'order_id' => $order->id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error enviando SMS de confirmación', [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);
        }
    }

    private function createOrder(array $validatedOrderData)
    {
        $order = Order::create([
            'customer_id' => $validatedOrderData['customer_id'],
            'voucher_type' => $validatedOrderData['voucher_type'],
            'billing_data' => $validatedOrderData['billing_data'] ?? null,
            'local_id' => $validatedOrderData['local_id'],
            'subtotal' => $validatedOrderData['subtotal'],
            'total' => $validatedOrderData['total_amount'],
            'order_date' => now(),
            'delivery_date' => $validatedOrderData['delivery_date'] ?? null,
            'order_number' => $validatedOrderData['purchaseNumber'] ?? null
        ]);

        foreach ($validatedOrderData['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item['product_variant_id'] ?? null,
                'product_id' => $item['product_id'] ?? null,
                'cake_flavor_id' => $item['cake_flavor_id'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
                'dedication_text' => $item['dedication_text'] ?? null,
                'delivery_date' => $item['delivery_date'] ?? null,
            ]);
        }

        Log::info('Orden creada', ['order' => $order, 'items' => $order->items]);

        return $order;
    }

}
