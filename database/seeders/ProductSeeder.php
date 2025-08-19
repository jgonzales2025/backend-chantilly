<?php

namespace Database\Seeders;

use App\Enum\ProductEnum;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(ProductEnum::cases() as $product){
            Product::create([
                'short_description' => $product->value,
                'large_description' => $product->large_description(),
                'product_type_id' => $product->type_id(),
                'category_id' => $product->category_id(),
                'min_price' => $product->min_price(),
                'max_price' => $product->max_price(),
                'theme_id' => $product->theme_id(),
                'image' => $product->image(),
                'status' => $product->status(),
                'best_status' => $product->best_status(),
                'product_link' => $product->product_link()
            ]);
        }
    }
}
