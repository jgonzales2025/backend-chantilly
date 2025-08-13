<?php

namespace App\Enum;

enum PageEnum: string
{
    case PAGE_01 = 'NOVEDADES';
    case PAGE_02 = 'TORTAS EN LINEA';
    case PAGE_03 = 'POSTRES';
    case PAGE_04 = 'BOCADITOS';
    case PAGE_05 = 'CONTACTANOS';
    case PAGE_06 = 'TORTAS TEMATICAS';
    case PAGE_07 = 'PROMOCIONES';

    public function link_view()
    {
        return match($this){
            self::PAGE_01 => '',
            self::PAGE_02 => 'Tortas',
            self::PAGE_03 => 'Postres',
            self::PAGE_04 => 'Bocaditos',
            self::PAGE_05 => 'Contactanos',
            self::PAGE_06 => 'TortasTematicas',
            self::PAGE_07 => 'Promociones'
        };
    }

    public function orden()
    {
        return match($this){
            self::PAGE_01 => 1,
            self::PAGE_02 => 2,
            self::PAGE_03 => 5,
            self::PAGE_04 => 6,
            self::PAGE_05 => 7,
            self::PAGE_06 => 3,
            self::PAGE_07 => 4
        };
    }

    public function status()
    {
        return match($this){
            self::PAGE_01 => true,
            self::PAGE_02 => true,
            self::PAGE_03 => true,
            self::PAGE_04 => true,
            self::PAGE_05 => true,
            self::PAGE_06 => true,
            self::PAGE_07 => true
        };
    }
}
