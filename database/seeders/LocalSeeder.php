<?php

namespace Database\Seeders;

use App\Enum\LocalEnum;
use App\Models\Local;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(LocalEnum::cases() as $local){
            Local::create([
                'name' => $local->value,
                'image' => $local->image(),
                'address' => $local->address(),
                'department' => $local->department(),
                'province' => $local->province(),
                'district' => $local->district(),
                'start_time' => $local->start_time(),
                'end_time' => $local->end_time(),
                'link_local' => $local->link_local(),
                'latitud' => $local->latitud(),
                'longitud' => $local->longitud()
            ]);
        }
    }
}
