<?php

namespace Database\Seeders;

use App\Enum\ThemeEnum;
use App\Models\Theme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(ThemeEnum::cases() as $theme){
            Theme::create([
                'name' => $theme->value,
                'image_url' => $theme->image_url()
            ]);
        }
    }
}
