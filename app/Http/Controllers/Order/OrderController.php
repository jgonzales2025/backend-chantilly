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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();

        return DB::transaction(function () use ($validatedData){
            $order = Order::create([
                'customer_id' => $validatedData['customer_id'],
                'subtotal' => $validatedData['subtotal'],
                'total' => $validatedData['total_amount'],
                'delivery_date' => $validatedData['delivery_date'],
                'status' => $validatedData['status']
            ]);

            foreach ($validatedData['items'] as $item){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'cake_flavor_id' => $item['cake_flavor_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'dedication_text' => $item['dedication_text'] ?? null
                ]);
            }

            return new JsonResponse([
                'message' => 'Orden creada con éxito',
                'order' => $order->load('items')
            ]);
        });
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
