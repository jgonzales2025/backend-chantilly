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
use App\Models\PointConversion;
use App\Models\PointHistory;
use App\Notifications\OrderPaid;
use App\Services\NiubizService;
use App\Services\SmsService;
use Exception;
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
                'environment' => $env
            ]
        ]);
    }

    /**
     * Obtener session para el frontend (checkout.js)
     */
    public function getSession(StoreOrderRequest $req)
    {
        $validatedOrderData = $req->validated();

        DB::beginTransaction();
        
        try {

            $transaction = NiubizTransaction::create([
                'amount' => $validatedOrderData['total_amount'],
                'currency' => 'PEN', 
                'status' => 'pending'
            ]);

            $validatedOrderData['purchaseNumber'] = (string) $transaction->id;

            // Generar el purchaseNumber
            /* $lastTransaction = NiubizTransaction::orderBy('id', 'desc')->first();
            $nextNumber = $lastTransaction ? $lastTransaction->id + 1 : 1;
            $validatedOrderData['purchaseNumber'] = (string) $nextNumber; */
           
            $transaction->update([
                'purchase_number' => $validatedOrderData['purchaseNumber']
            ]);

            $customer = Customer::find($validatedOrderData['customer_id']);

            // Calcular la cantidad de días registrado del cliente hasta el momento de la compra
            if ($customer && $customer->created_at) {
                $diasRegistrado = now()->diffInDays($customer->created_at);
            }
            // A través del servicio "createSession" se obtendrá el token de sesión de niubiz
            $result = $this->niubiz->createSession(
                $validatedOrderData['total_amount'],
                $customer,
                $diasRegistrado,
                $transaction
            );

            DB::commit();

            // Se retorna al frontend los datos necesarios para el checkout
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

            return response()->json([
                'success' => false,
                'message' => 'Error al inicializar el pago',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    public function payResponse(Request $request)
    {
        try {
            // Los datos de la transacción llegan por POST
            $data = $request->all();

            $transactionToken = $data['transactionToken'] ?? null;
            $customerEmail = $data['customerEmail'] ?? null;
            $channel = $data['channel'] ?? null;

            $transaction = NiubizTransaction::where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->first();

            $purchaseNumber = $transaction ? $transaction->purchase_number : null;

            $amount = $transaction ? $transaction->amount : null;

            // Generar un token único temporal
            $tempToken = Str::random(32);
            
            // GUARDAR en CACHE (expira en 5 minutos)
            cache()->put("payment_result_{$tempToken}", [
                'tokenId' => $transactionToken,
                'purchaseNumber' => $purchaseNumber,
                'customerEmail' => $customerEmail,
                'channel' => $channel,
                'amount' => $amount,
                'currency' => 'PEN',
                'autoProcess' => true,
                'timestamp' => now()
            ], now()->addMinutes(5));

            // Redirigir CON el token temporal en la URL
            $redirectUrl = config('app.frontend_url') . '/checkout/payconfirmation?token=' . $tempToken;
            
            return redirect($redirectUrl);

        } catch (Exception $e) {
            Log::error('Error procesando respuesta de Niubiz: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error procesando respuesta de pago',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }
    
    /**
     * Obtener datos de pago
     */
    public function getPaymentData(Request $request)
    {
        try {
            $token = $request->query('token');

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token requerido'
                ], 400);
            }

            // Obtener datos del cache usando el token
            $paymentResult = cache()->get("payment_result_{$token}");

            if (!$paymentResult) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido o expirado'
                ], 404);
            }

            // Eliminar del caché después de obtener (uso único)
            cache()->forget("payment_result_{$token}");

            return response()->json([
                'success' => true,
                'data' => $paymentResult
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo datos de pago', [
                'error' => $e->getMessage(),
                'token' => $request->query('token')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos de pago',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Procesar pago
     */
    public function pay(Request $request)
    {
        $validated = $request->validate([
            'tokenId' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'purchaseNumber' => 'required|string|exists:niubiz_transactions,purchase_number',
            'order_data' => 'required|array'
        ]);
        DB::beginTransaction();
        
        try {
            // Datos del pedido
            $validatedOrderData = $validated['order_data'];

            // Se crea el pedido
            $order = $this->createOrder($validatedOrderData);

            // Verificar que la transacción existe con el purchase number
            $transaction = NiubizTransaction::where('purchase_number', $validated['purchaseNumber'])->first();
            $transaction->update(['order_id' => $order->id]); // Se actualiza la transaccion con el order_id
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transacción no encontrada'
                ], 404);
            }

            if ($transaction->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'La transacción ya fue procesada',
                    'status' => 'failed'
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
                    $this->sendPaymentNotifications($transaction->order, $transaction); // Se envían los datos para el envío de correo y mensaje
                } else {
                    $transaction->order->update(['payment_status' => 'failed']);
                }
            }

            DB::commit();
            
            $transaction->refresh();

            // Preparar respuesta
            $responseData = [
                'success' => $isSuccess,
                'data' => $transaction->getNiubizResponseFormatted(),
                'message' => $isSuccess ? 'Pago procesado exitosamente' : 'Pago rechazado'
            ];

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

    /**
     * Crear una nueva orden
     */
    private function createOrder(array $validatedOrderData)
    {
        $customer = Customer::lockForUpdate()->find($validatedOrderData['customer_id']);
        if (!$customer) {
            throw new \Exception('Cliente no encontrado');
        }

        if ($validatedOrderData['is_canje'] == true) {
            if ($customer->points < $validatedOrderData['points_used']) {
                throw new \Exception('Puntos insuficientes para realizar el canje');
            }
        }

        $order = Order::create([
            'customer_id' => $validatedOrderData['customer_id'],
            'voucher_type' => $validatedOrderData['voucher_type'],
            'billing_data' => $validatedOrderData['billing_data'] ?? null,
            'local_id' => $validatedOrderData['local_id'],
            'subtotal' => $validatedOrderData['subtotal'],
            'total' => $validatedOrderData['total_amount'],
            'order_date' => now(),
            'delivery_date' => $validatedOrderData['delivery_date'] ?? null,
            'order_number' => $validatedOrderData['purchaseNumber'] ?? null,
            'status_id' => 1
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
        
        if ($validatedOrderData['is_canje'] == false) {
            $conversionRate = PointConversion::first();
            $pointHistory = PointHistory::create([
                'order_id' => $order->id,
                'order_date' => now(),
                'sale_amount' => $order->total,
                'conversion_rate' => $conversionRate->soles_to_points,
                'points_earned' => floor($order->total / $conversionRate->soles_to_points),
                'point_type' => 'Acumulado'
            ]);
            $customer->increment('points', $pointHistory->points_earned);
        } else {
            $conversionRate = PointConversion::first();
            $pointHistory = PointHistory::create([
                'order_id' => $order->id,
                'order_date' => now(),
                'sale_amount' => $order->total,
                'conversion_rate' => $conversionRate->points_to_soles,
                'points_earned' => -floor($validatedOrderData['points_used'] / $conversionRate->points_to_soles),
                'point_type' => 'Canje'
            ]);
            $customer->decrement('points', abs($pointHistory->points_earned));
        }

        return $order;
    }

}
