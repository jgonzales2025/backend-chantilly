<?php

namespace App\Enum;

enum DocumentTypeEnum :string
{
    case DOCUMENTTYPE_01 = 'DNI';
    case DOCUMENTTYPE_02 = 'PASAPORTE';
    case DOCUMENTTYPE_03 = 'CARNET DE EXTRANJERIA';
    case DOCUMENTTYPE_04 = 'RUC';
}
