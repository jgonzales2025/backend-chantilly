<?php

namespace Database\Seeders;

use App\Enum\CakeFlavorEnum;
use App\Models\CakeFlavor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CakeFlavorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(CakeFlavorEnum::cases() as $flavor) {
            CakeFlavor::create([
                'name' => $flavor->value,
                'status' => $flavor->status()
            ]);
        }
    }
}
