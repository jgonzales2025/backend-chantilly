<?php

namespace App\Enum;

enum OrderStatusEnum: string
{
    case ORDERSTATUS_01 = 'Pendiente';
    case ORDERSTATUS_02 = 'Preparación';
    case ORDERSTATUS_03 = 'Listo';
    case ORDERSTATUS_04 = 'Entregado';

    public function order()
    {
        return match($this){
            self::ORDERSTATUS_01 => 1,
            self::ORDERSTATUS_02 => 2,
            self::ORDERSTATUS_03 => 3,
            self::ORDERSTATUS_04 => 4
        };
    }

    public function status()
    {
        return match($this){
            self::ORDERSTATUS_01 => 'Activo',
            self::ORDERSTATUS_02 => 'Activo',
            self::ORDERSTATUS_03 => 'Activo',
            self::ORDERSTATUS_04 => 'Activo'
        };
    }
}
