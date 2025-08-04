<?php

namespace Database\Seeders;

use App\Enum\DocumentTypeEnum;
use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(DocumentTypeEnum::cases() as $doc){
            DocumentType::create([
                'name' => $doc->value
            ]);
        }
    }
}
