<?php

namespace App\Enum;

enum PaymentStatusEnum: string
{
    case PENDING = 'Pendiente';
    case PAID = 'Pagado';
    case FAILED = 'Fallido';

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
