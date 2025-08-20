<?php

namespace App\Http\Controllers\Payment;

use App\Enum\PaymentStatusEnum;
use App\Enum\TransactionStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\NiubizTransaction;
use App\Models\Order;
use App\Services\NiubizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $niubiz;

    public function __construct(NiubizService $niubiz)
    {
        $this->niubiz = $niubiz;
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
     * Actualizado con BD y validaciones mejoradas
     */
    public function getSession(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'order_id' => 'sometimes|exists:orders,id',
            'purchaseNumber' => 'sometimes|string|unique:niubiz_transactions,purchase_number',
            'description' => 'sometimes|string|max:255'
        ]);

        DB::beginTransaction();
        
        try {
            // Generar purchaseNumber si no se proporciona
            if (!isset($validated['purchaseNumber'])) {
                $validated['purchaseNumber'] = 'PUR-' . time() . '-' . Str::random(6);
            }

            // Verificar si la orden existe y no está ya pagada
            $order = null;
            if (isset($validated['order_id'])) {
                $order = Order::find($validated['order_id']);
                
                if ($order && $order->isPaid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La orden ya está pagada'
                    ], 400);
                }

                // Actualizar estado de la orden a pendiente de pago
                if ($order) {
                    $order->update(['payment_status' => PaymentStatusEnum::PENDING]);
                }
            }

            // Crear sesión con Niubiz (esto creará el registro en niubiz_transactions)
            $result = $this->niubiz->createSession(
                $validated['amount'],
                $validated['purchaseNumber'],
                $validated['order_id'] ?? null
            );

            DB::commit();

            Log::info('Sesión de pago iniciada', [
                'purchase_number' => $validated['purchaseNumber'],
                'order_id' => $validated['order_id'] ?? null,
                'amount' => $validated['amount']
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'sessionToken' => $result['sessionToken'] ?? null,
                    'purchase_number' => $validated['purchaseNumber'],
                    'merchant_id' => config('niubiz.merchant_id'),
                    'amount' => $validated['amount']
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error obteniendo sesión de pago', [
                'error' => $e->getMessage(),
                'request' => $validated
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
            'purchaseNumber' => 'required|string|exists:niubiz_transactions,purchase_number'
        ]);

        DB::beginTransaction();
        
        try {
            // Verificar que la transacción existe y está pendiente
            $transaction = NiubizTransaction::where('purchase_number', $validated['purchaseNumber'])->first();
            
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
                    $transaction->order->markAsPaid('niubiz');
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
                    'purchase_number' => $validated['purchaseNumber']
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

}
