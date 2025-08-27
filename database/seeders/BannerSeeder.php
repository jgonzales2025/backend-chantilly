<?php

namespace Database\Seeders;

use App\Enum\BannerEnum;
use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(BannerEnum::cases() as $banner){
            Banner::create([
                'title' => $banner->value,
                'image_path' => $banner->image_path(),
                'display_order' => $banner->display_order()
            ]);
        }
    }
}
