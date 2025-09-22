<?php

namespace Database\Seeders;

use App\Enum\OrderEnum;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(OrderEnum::cases() as $order){
            Order::create([
                'customer_id' => $order->customer_id(),
                'order_number' => $order->value,
                'voucher_type' => $order->voucher_type(),
                'billing_data' => $order->billing_data(),
                'local_id' => $order->local_id(),
                'subtotal' => $order->subtotal(),
                'total' => $order->total(),
                'order_date' => $order->order_date(),
                'status_id' => $order->status(),
                'payment_method' => $order->payment_method(),
                'payment_status' => $order->payment_status(),
                'delivery_date' => $order->delivery_date()
            ]);
        }
    }
}
