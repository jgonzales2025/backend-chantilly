<?php

namespace App\Enum;

enum BannerEnum: string
{
    case BANNER_01 = 'bocaditos01';
    case BANNER_02 = 'bocaditos02';
    case BANNER_03 = 'banner_shower';
    case BANNER_04 = 'promocion_fiesta';
    case BANNER_05 = 'bocaditos_dulces';
    case BANNER_06 = 'bocaditos_gourmet01';
    case BANNER_07 = 'bocaditos_gourmet02';
    case BANNER_08 = 'descuento_martes';
    case BANNER_09 = 'tortas_confirmacion';
    case BANNER_10 = 'tortas_tematica';
    case BANNER_11 = 'bodatitos_y_mas';
    case BANNER_12 = 'paneton';

    public function image_path()
    {
        return match($this) {
            self::BANNER_01 => 'banners/bocaditos01.jpg',
            self::BANNER_02 => 'banners/bocaditos02.jpg',
            self::BANNER_03 => 'banners/banner_shower.jpg',
            self::BANNER_04 => 'banners/promocion_fiesta.jpg',
            self::BANNER_05 => 'banners/bocaditos_dulces.jpg',
            self::BANNER_06 => 'banners/bocaditos_gourmet01.jpg',
            self::BANNER_07 => 'banners/bocaditos_gourmet02.jpg',
            self::BANNER_08 => 'banners/descuentomartes.jpg',
            self::BANNER_09 => 'banners/tortas_confirmacion.jpg',
            self::BANNER_10 => 'banners/chantilly4-02.jpg',
            self::BANNER_11 => 'banners/chantilly4-03.jpg',
            self::BANNER_12 => 'banners/das32222-03.jpg',
        };
    }

    public function display_order()
    {
        return match($this) {
            self::BANNER_01 => 1,
            self::BANNER_02 => 2,
            self::BANNER_03 => 3,
            self::BANNER_04 => 4,
            self::BANNER_05 => 5,
            self::BANNER_06 => 6,
            self::BANNER_07 => 7,
            self::BANNER_08 => 8,
            self::BANNER_09 => 9,
            self::BANNER_10 => 10,
            self::BANNER_11 => 11,
            self::BANNER_12 => 12,
        };
    }
}
