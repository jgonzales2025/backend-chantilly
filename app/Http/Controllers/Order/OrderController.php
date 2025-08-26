<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
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
        $customerId = $request->query('customer_id');
        $orderNumber = $request->query('order_number');
        $dateFilter = $request->query('date_filter');
        $orders = Order::with('items.product', 'items.productVariant', 'local')
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customer_id', $customerId);
            })
            ->when($orderNumber, function ($query, $orderNumber) {
                return $query->where('order_number', $orderNumber);
            })
            ->when($dateFilter, function ($query, $dateFilter) {
                switch ($dateFilter) {
                    case 'ultimos_30_dias':
                        return $query->where('order_date', '>=', now()->subDays(30)->startOfDay());
                    case 'ultimos_3_meses':
                        return $query->where('order_date', '>=', now()->subMonths(3)->startOfDay());
                    case 'ultimos_6_meses':
                        return $query->where('order_date', '>=', now()->subMonths(6)->startOfDay());
                    case '2025':
                        return $query->whereYear('order_date', 2025);
                    default:
                        return $query;
                }
            })
            ->orderBy('order_date', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return new JsonResponse([
                'message' => 'No se encontraron pedidos para este cliente'
            ], 404);
        }

        return new JsonResponse([
            'customer_id' => $customerId,
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

            Log::info('Orden creada', ['order' => $order, 'items' => $order->items]);

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
        $order = Order::with('items')->find($id);

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
