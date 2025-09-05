<?php

namespace Database\Seeders;

use App\Enum\ProductVariantEnum;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(ProductVariantEnum::cases() as $variant){
            $createdProduct = ProductVariant::create([
                'product_id' => $variant->productId(),
                'description' => $variant->value,
                'portions' => $variant->portions(),
                'size_portion' => $variant->sizeportion(),
                'price' => $variant->price(),
                'hours' => $variant->hours(),
            ]);

            // Agregar imagen a la tabla images usando el trait
            $createdProduct->addImage(
                $variant->image(), // path/url de la imagen
                true, // es imagen principal
                0 // sort order
            );
        }
    }
}
