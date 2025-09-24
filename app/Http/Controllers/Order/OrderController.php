<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PointConversion;
use App\Models\PointHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Listar pedidos.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'customer_id' => $request->query('customer_id'),
            'order_number' => $request->query('order_number'),
            'date_filter' => $request->query('date_filter'),
        ];

        $orders = Order::with('items.product', 'items.productVariant', 'local', 'status', 'pointHistories')
            ->filterOrders($filters)
            ->orderBy('order_date', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return new JsonResponse([
                'message' => 'No se encontraron pedidos para este cliente'
            ], 404);
        }

        return new JsonResponse([
            'orders' => OrderResource::collection($orders)
        ], 200);
    }

    /**
     * Crear pedidos.
     */
    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        return DB::transaction(function () use ($validatedData){
            $order = Order::create([
                'customer_id' => $validatedData['customer_id'],
                'voucher_type' => $validatedData['voucher_type'],
                'billing_data' => $validatedData['billing_data'] ?? null,
                'local_id' => $validatedData['local_id'],
                'subtotal' => $validatedData['subtotal'],
                'total' => $validatedData['total_amount'],
                'order_date' => now(),
                'delivery_date' => $validatedData['delivery_date'] ?? null,
                'order_number' => $validatedData['purchase_number'] ?? null,
                'status_id' => 1
            ]);
            foreach ($validatedData['items'] as $item){
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

            if ($validatedData['is_canje'] === false) {
                $conversionRate = PointConversion::first();
                $pointHistory = PointHistory::create([
                    'order_id' => $order->id,
                    'order_date' => now(),
                    'sale_amount' => $order->total,
                    'conversion_rate' => $conversionRate->soles_to_points,
                    'points_earned' => floor($order->total / $conversionRate->soles_to_points),
                    'point_type' => floor($order->total / $conversionRate->soles_to_points) > 0 ? 'Acumulado' : 'No acumula'
                ]);
                $order->customer->increment('points', $pointHistory->points_earned);
            } else {
                $conversionRate = PointConversion::first();
                $pointHistory = PointHistory::create([
                    'order_id' => $order->id,
                    'order_date' => now(),
                    'sale_amount' => $order->total,
                    'conversion_rate' => $conversionRate->points_to_soles,
                    'points_earned' => -floor($validatedData['points_used'] / $conversionRate->points_to_soles),
                    'point_type' => 'Canje'
                ]);
                $order->customer->decrement('points', abs($pointHistory->points_earned));
            }

            return new JsonResponse([
                'message' => 'Orden creada con éxito',
                'order' => new OrderResource($order->load('items'))
            ]);
        });
        
    }

    /**
     * Mostrar pedido por id.
     */
    public function show($id)
    {
        $order = Order::with('items', 'status')->find($id);

        if (!$order) {
            return new JsonResponse(['message' => 'Pedido no encontrado'], 404);
        }

        return new JsonResponse($order, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
