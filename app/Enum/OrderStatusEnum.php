<?php

namespace App\Enum;

enum OrderStatusEnum: string
{
    case ORDERSTATUS_01 = 'Pendiente';
    case ORDERSTATUS_02 = 'Preparación';
    case ORDERSTATUS_03 = 'En tienda';
    case ORDERSTATUS_04 = 'Entregado';
    case ORDERSTATUS_05 = 'Cancelado';
    case ORDERSTATUS_06 = 'Devuelto';

    public function order()
    {
        return match($this){
            self::ORDERSTATUS_01 => 1,
            self::ORDERSTATUS_02 => 2,
            self::ORDERSTATUS_03 => 3,
            self::ORDERSTATUS_04 => 4,
            self::ORDERSTATUS_05 => 5,
            self::ORDERSTATUS_06 => 6
        };
    }

    public function status()
    {
        return match($this){
            self::ORDERSTATUS_01 => 'Activo',
            self::ORDERSTATUS_02 => 'Activo',
            self::ORDERSTATUS_03 => 'Activo',
            self::ORDERSTATUS_04 => 'Activo',
            self::ORDERSTATUS_05 => 'Activo',
            self::ORDERSTATUS_06 => 'Activo'
        };
    }
}
