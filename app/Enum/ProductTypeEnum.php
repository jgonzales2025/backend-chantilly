<?php

namespace App\Enum;

enum ProductTypeEnum: string
{
    case PRODUCTTYPE_01 = 'TORTA EN LINEA';
    case PRODUCTTYPE_02 = 'TORTA TEMATICA';
    case PRODUCTTYPE_03 = 'POSTRE';
    case PRODUCTTYPE_04 = 'BOCADITO';
    case PRODUCTTYPE_05 = 'ACCESORIO';
    case PRODUCTTYPE_06 = 'PROMOCIONES';
}
