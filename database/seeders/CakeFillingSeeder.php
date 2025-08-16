<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CakeFillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cake_flavor_filling')->insert([
            ['cake_flavor_id' => 1, 'filling_id' => 3],
            ['cake_flavor_id' => 1, 'filling_id' => 1],
            ['cake_flavor_id' => 2, 'filling_id' => 1],
            ['cake_flavor_id' => 2, 'filling_id' => 2],
            ['cake_flavor_id' => 3, 'filling_id' => 2],
            ['cake_flavor_id' => 4, 'filling_id' => 3],
            ['cake_flavor_id' => 5, 'filling_id' => 1],
            ['cake_flavor_id' => 6, 'filling_id' => 3],
            ['cake_flavor_id' => 7, 'filling_id' => 3],
        ]);

    }
}
