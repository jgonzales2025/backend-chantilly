<?php

namespace Database\Seeders;

use App\Enum\OrderStatusEnum;
use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(OrderStatusEnum::cases() as $status){
            OrderStatus::create([
                'name' => $status->value,
                'order' => $status->order(),
                'status' => $status->status()
            ]);
        }
    }
}
