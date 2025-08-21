<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Listar pedidos.
     */
    public function index(): JsonResponse
    {
        $orders = Order::all();

        if ($orders->isEmpty()){
            return new JsonResponse(['message' => 'No hay pedidos registrados']);
        }

        return new JsonResponse($orders->load('items'), 200);
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
                'order_date' => now()
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
                    'delivery_date' => $item['delivery_date']
                ]);
            }

            return new JsonResponse([
                'message' => 'Orden creada con éxito',
                'order' => $order->load('items')
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
