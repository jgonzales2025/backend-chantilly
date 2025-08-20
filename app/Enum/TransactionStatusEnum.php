<?php

namespace App\Enum;

enum TransactionStatusEnum: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';

    // Método para obtener todos los valores
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    // Método para obtener colores (útil para frontend)
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::SUCCESS => 'green',
            self::FAILED => 'red',
            self::CANCELLED => 'gray',
        };
    }

    // Método para obtener etiquetas legibles
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::SUCCESS => 'Exitoso',
            self::FAILED => 'Fallido',
            self::CANCELLED => 'Cancelado',
        };
    }

    // Verificar si es un estado final
    public function isFinal(): bool
    {
        return match($this) {
            self::SUCCESS, self::FAILED, self::CANCELLED => true,
            self::PENDING => false,
        };
    }
}
