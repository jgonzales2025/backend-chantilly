<?php

namespace Database\Seeders;

use App\Models\BannerSecundary;
use App\Models\Order;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
        $this->call([
            CompanySeeder::class,
            DocumentTypeSeeder::class,
            CategorySeeder::class,
            ProductTypeSeeder::class,
            ThemeSeeder::class,
            FillingSeeder::class,
            CakeFlavorSeeder::class,
            CakeFillingSeeder::class,
            PageSeeder::class,
            LocalSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            ProductVariantSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            TransactionSeeder::class,
            UserSeeder::class,
            BannerSecundarySeeder::class
        ]);
        
    }
}
