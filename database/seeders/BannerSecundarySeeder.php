<?php

namespace Database\Seeders;

use App\Models\BannerSecundary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSecundarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BannerSecundary::create([
            'title' => '¡Pedidos Personalizados!',
            'description' => 'Personaliza tu producto con nosotros, mándanos tu diseño y descripción de lo que necesites, nuestro equipo te ayudará con la cotización del producto.',
            'image_path' => 'banners/banner2.jpg',
            'image_path_movil' => 'banners/fondo-banner2.jpg'
        ]);
    }
}
