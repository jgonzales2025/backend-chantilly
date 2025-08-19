<?php

namespace App\Enum;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::PAID => 'green',
            self::FAILED => 'red',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::PAID => 'Pagado',
            self::FAILED => 'Fallido',
        };
    }
}
