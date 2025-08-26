<?php

namespace App\Enum;

enum OrderItemEnum: string
{
    case ORDERITEM_01 = 'Happy Birthday!';
    case ORDERITEM_02 = 'Congratulations!';
    case ORDERITEM_03 = 'Thank You!';
    case ORDERITEM_04 = 'Get Well Soon!';
    case ORDERITEM_05 = 'Happy Anniversary!';
    case ORDERITEM_06 = 'New Year Wishes!';
    case ORDERITEM_07 = 'Sympathy Messages';

    public function product_variant_id()
    {
        return match ($this) {
            self::ORDERITEM_01 => 1,
            self::ORDERITEM_02 => 2,
            self::ORDERITEM_03 => 3,
            self::ORDERITEM_04 => null,
            self::ORDERITEM_05 => null,
            self::ORDERITEM_06 => null,
            self::ORDERITEM_07 => null,
        };
    }
    
    public function product_id()
    {
        return match ($this) {
            self::ORDERITEM_01 => null,
            self::ORDERITEM_02 => null,
            self::ORDERITEM_03 => null,
            self::ORDERITEM_04 => 1,
            self::ORDERITEM_05 => 2,
            self::ORDERITEM_06 => 1,
            self::ORDERITEM_07 => 2,
        };
    }

    public function cake_flavor_id()
    {
        return match ($this) {
            self::ORDERITEM_01 => null,
            self::ORDERITEM_02 => null,
            self::ORDERITEM_03 => null,
            self::ORDERITEM_04 => 1,
            self::ORDERITEM_05 => 1,
            self::ORDERITEM_06 => 2,
            self::ORDERITEM_07 => 2,
        };
    }

    public function quantity()
    {
        return match ($this) {
            self::ORDERITEM_01 => 1,
            self::ORDERITEM_02 => 1,
            self::ORDERITEM_03 => 1,
            self::ORDERITEM_04 => 1,
            self::ORDERITEM_05 => 1,
            self::ORDERITEM_06 => 1,
            self::ORDERITEM_07 => 1,
        };
    }

    public function unit_price()
    {
        return match ($this) {
            self::ORDERITEM_01 => 15.00,
            self::ORDERITEM_02 => 15.00,
            self::ORDERITEM_03 => 30.00,
            self::ORDERITEM_04 => 15.00,
            self::ORDERITEM_05 => 40.00,
            self::ORDERITEM_06 => 15.00,
            self::ORDERITEM_07 => 50.00,
        };
    }

    public function subtotal()
    {
        return match ($this) {
            self::ORDERITEM_01 => 15.00,
            self::ORDERITEM_02 => 15.00,
            self::ORDERITEM_03 => 30.00,
            self::ORDERITEM_04 => 15.00,
            self::ORDERITEM_05 => 40.00,
            self::ORDERITEM_06 => 15.00,
            self::ORDERITEM_07 => 50.00,
        };
    }

    public function order_id()
    {
        return match ($this) {
            self::ORDERITEM_01 => 1,
            self::ORDERITEM_02 => 1,
            self::ORDERITEM_03 => 2,
            self::ORDERITEM_04 => 2,
            self::ORDERITEM_05 => 3,
            self::ORDERITEM_06 => 4,
            self::ORDERITEM_07 => 5,
        };
    }
}
