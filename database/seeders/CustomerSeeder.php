<?php

namespace Database\Seeders;

use App\Enum\CustomerEnum;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(CustomerEnum::cases() as $customer){
            Customer::create([
                'name' => $customer->value,
                'lastname' => $customer->lastname(),
                'id_document_type' => $customer->document_type(),
                'document_number' => $customer->document_number(),
                'email' => $customer->email(),
                'password' => $customer->password(),
                'address' => $customer->address(),
                'phone' => $customer->phone(),
                'department' => $customer->department(),
                'province' => $customer->province(),
                'district' => $customer->district(),
            ]);
        }
    }
}
