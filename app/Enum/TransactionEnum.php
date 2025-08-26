<?php

namespace App\Enum;

enum TransactionEnum: int
{
    case TRANSACTION_01 = 1;
    case TRANSACTION_02 = 2;
    case TRANSACTION_03 = 3;
    case TRANSACTION_04 = 4;
    case TRANSACTION_05 = 5;

    public function amount()
    {
        return match($this) {
            self::TRANSACTION_01 => 100,
            self::TRANSACTION_02 => 200,
            self::TRANSACTION_03 => 300,
            self::TRANSACTION_04 => 400,
            self::TRANSACTION_05 => 500,
        };
    }
}
