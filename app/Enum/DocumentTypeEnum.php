<?php

namespace App\Enum;

enum DocumentTypeEnum :string
{
    case DOCUMENTTYPE_01 = 'DNI';
    case DOCUMENTTYPE_02 = 'C.EXT';
    case DOCUMENTTYPE_03 = 'PAS';
    case DOCUMENTTYPE_04 = 'PTP';
}
