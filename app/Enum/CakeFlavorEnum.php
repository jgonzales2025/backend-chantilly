<?php

namespace App\Enum;

enum CakeFlavorEnum: string
{
    case CAKEFLAVOR_01 = "Keke de Vainilla con Chispas";
    case CAKEFLAVOR_02 = "Keke de Vainilla";
    case CAKEFLAVOR_03 = "Keke de Novia";
    case CAKEFLAVOR_04 = "Keke Red Velvet";
    case CAKEFLAVOR_05 = "Keke Inglés";
    case CAKEFLAVOR_06 = "Keke de Chocolate";
    case CAKEFLAVOR_07 = "Keke de Zanahoria";

    public function status()
    {
        return match($this) {
            self::CAKEFLAVOR_01 => 1,
            self::CAKEFLAVOR_02 => 1,
            self::CAKEFLAVOR_03 => 1,
            self::CAKEFLAVOR_04 => 1,
            self::CAKEFLAVOR_05 => 1,
            self::CAKEFLAVOR_06 => 1,
            self::CAKEFLAVOR_07 => 1,
        };
    }
}
