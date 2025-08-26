<?php

namespace Database\Seeders;

use App\Enum\TransactionEnum;
use App\Models\NiubizTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (TransactionEnum::cases() as $transaction) {
            NiubizTransaction::create([
                'purchase_number' => $transaction->value,
                'amount' => $transaction->amount()
            ]);
        }
    }
}
