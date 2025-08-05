<?php

namespace Database\Seeders;

use App\Enum\FillingEnum;
use App\Models\Filling;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(FillingEnum::cases() as $fill){
            Filling::create([
                'name' => $fill->value
            ]);
        }
    }
}
