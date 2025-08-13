<?php

namespace App\Enum;

enum ThemeEnum: string
{
    case THEME_01 = 'INFANTILES';
    case THEME_02 = 'MUJER';
    case THEME_03 = 'HOMBRE';
    case THEME_04 = 'BAUTIZO';
    case THEME_05 = 'PROFESIONES';
    case THEME_06 = 'ENAMORADOS';
    case THEME_07 = 'BABYSHOWER';

    public function image_url()
    {
        return match($this){
            self::THEME_01 => 'iconos-05.png',
            self::THEME_02 => 'iconos-03.png',
            self::THEME_03 => 'iconos-04.png',
            self::THEME_04 => 'iconos-07.png',
            self::THEME_05 => 'iconos-06.png',
            self::THEME_06 => 'iconos-13.png',
            self::THEME_07 => 'iconos-10.png',
        };
    }
}
