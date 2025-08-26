<?php

namespace App\Enum;

enum OrderEnum: int
{
    case ORDER_01 = 1;
    case ORDER_02 = 2;
    case ORDER_03 = 3;
    case ORDER_04 = 4;
    case ORDER_05 = 5;

    public function customer_id()
    {
        return match($this) {
            self::ORDER_01 => 1,
            self::ORDER_02 => 1,
            self::ORDER_03 => 1,
            self::ORDER_04 => 1,
            self::ORDER_05 => 1,
        };
    }

    public function voucher_type()
    {
        return match($this) {
            self::ORDER_01 => 'BOLETA',
            self::ORDER_02 => 'BOLETA',
            self::ORDER_03 => 'FACTURA',
            self::ORDER_04 => 'BOLETA',
            self::ORDER_05 => 'FACTURA',
        };
    }

    public function billing_data()
    {
        return match($this) {
            self::ORDER_01 => null,
            self::ORDER_02 => null,
            self::ORDER_03 => ['ruc' => '20100073723', 'razon_social' => 'CPPQ S.A.C.', 'tax_address' => '789 Oak St'],
            self::ORDER_04 => null,
            self::ORDER_05 => ['ruc' => '20493918541', 'razon_social' => 'CASA HOGAR S.A.C.', 'tax_address' => '654 Maple St'],
        };
    }

    public function local_id()
    {
        return match($this) {
            self::ORDER_01 => 1,
            self::ORDER_02 => 1,
            self::ORDER_03 => 2,
            self::ORDER_04 => 2,
            self::ORDER_05 => 1,
        };
    }

    public function subtotal()
    {
        return match($this) {
            self::ORDER_01 => 30,
            self::ORDER_02 => 45,
            self::ORDER_03 => 55,
            self::ORDER_04 => 45,
            self::ORDER_05 => 50,
        };
    }

    public function total()
    {
        return match($this) {
            self::ORDER_01 => 30,
            self::ORDER_02 => 45,
            self::ORDER_03 => 55,
            self::ORDER_04 => 45,
            self::ORDER_05 => 50,
        };
    }

    public function order_date()
    {
        return match($this) {
            self::ORDER_01 => '2023-01-01',
            self::ORDER_02 => '2023-02-02',
            self::ORDER_03 => '2024-04-03',
            self::ORDER_04 => '2025-04-04',
            self::ORDER_05 => '2025-08-05',
        };
    }

    public function status()
    {
        return match($this) {
            self::ORDER_01 => 1,
            self::ORDER_02 => 1,
            self::ORDER_03 => 1,
            self::ORDER_04 => 1,
            self::ORDER_05 => 1,
        };
    }

    public function payment_method()
    {
        return match($this) {
            self::ORDER_01 => 'Niubiz',
            self::ORDER_02 => 'Niubiz',
            self::ORDER_03 => 'Niubiz',
            self::ORDER_04 => 'Niubiz',
            self::ORDER_05 => 'Niubiz',
        };
    }

    public function payment_status()
    {
        return match($this) {
            self::ORDER_01 => 'Pagado',
            self::ORDER_02 => 'Pagado',
            self::ORDER_03 => 'Pagado',
            self::ORDER_04 => 'Pagado',
            self::ORDER_05 => 'Pagado',
        };
    }

    public function delivery_date()
    {
        return match($this) {
            self::ORDER_01 => '2023-01-05',
            self::ORDER_02 => '2023-02-06',
            self::ORDER_03 => '2024-04-07',
            self::ORDER_04 => '2025-04-08',
            self::ORDER_05 => '2025-08-09',
        };
    }
}
