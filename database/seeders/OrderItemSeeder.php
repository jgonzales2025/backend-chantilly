<?php

namespace Database\Seeders;

use App\Enum\OrderItemEnum;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(OrderItemEnum::cases() as $item){
            OrderItem::create([
                'order_id' => $item->order_id(),
                'product_variant_id' => $item->product_variant_id(),
                'product_id' => $item->product_id(),
                'cake_flavor_id' => $item->cake_flavor_id(),
                'quantity' => $item->quantity(),
                'unit_price' => $item->unit_price(),
                'subtotal' => $item->subtotal(),
                'dedication_text' => $item->value
            ]);
        }
    }
}
