<?php

namespace Database\Seeders;

use App\Enum\ProductTypeEnum;
use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(ProductTypeEnum::cases() as $prod){
            ProductType::create([
                'name' => $prod->value
            ]);
        }
    }
}
