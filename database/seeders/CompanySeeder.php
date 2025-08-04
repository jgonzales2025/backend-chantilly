<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            'businnes_name'      => 'Mi Empresa SAC',
            'ruc'                => '20123456789',
            'number_whatsapp'    => '987654321',
            'number_whatsapp1'   => '912345678',
            'about us'           => 'Somos una empresa dedicada al desarrollo de software.',
            'facebook'           => 'https://facebook.com/miempresa',
            'instagram'          => 'https://instagram.com/miempresa',
            'twitter'            => 'https://twitter.com/miempresa',
            'tiktok'             => 'https://tiktok.com/@miempresa',
            'logo_header'        => 'logo_header.png',
            'logo_footer'        => 'logo_footer.png',
        ]);
    }
}
