<?php

namespace Database\Seeders;

use App\Enum\PageEnum;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(PageEnum::cases() as $page){
            Page::create([
                'name' => $page->value,
                'link_view' => $page->link_view(),
                'orden' => $page->orden(),
                'status' => $page->status()
            ]);
        }
    }
}
