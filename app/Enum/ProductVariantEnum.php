<?php

namespace App\Enum;

enum ProductVariantEnum: string
{
    case PRODUCTVARIANT_01 = 'TORTA SELVA NEGRA ENTERA 26';
    case PRODUCTVARIANT_02 = 'TORTA SELVA NEGRA ENTERA 36x24';
    case PRODUCTVARIANT_03 = 'TORTA SUBLIME 26';
    case PRODUCTVARIANT_04 = 'TORTA SUBLIME 22';
    case PRODUCTVARIANT_05 = 'TORTA SUBLIME 18';
    case PRODUCTVARIANT_06 = 'TORTA SUBLIME 32x24';
    case PRODUCTVARIANT_07 = 'TORTA TRES LECHES DE COCO D18';
    case PRODUCTVARIANT_08 = 'TORTA TRES LECHES DOBLE CHOCOLATE D26';
    case PRODUCTVARIANT_09 = 'TORTA TRES LECHES DOBLE CHOCOLATE D22';
    case PRODUCTVARIANT_10 = 'TORTA TRES LECHES DOBLE CHOCOLATE D18';
    case PRODUCTVARIANT_11 = 'TORTA TRES LECHES DE COCO D26';
    case PRODUCTVARIANT_12 = 'TORTA TRES LECHES DE COCO D22';
    case PRODUCTVARIANT_13 = 'TORTA DE TRES LECHE DE FRESA D18';
    case PRODUCTVARIANT_14 = 'TORTA DE CHOCOLATE D22';
    case PRODUCTVARIANT_15 = 'CHEESCAKE DE MARACUYA';
    case PRODUCTVARIANT_16 = 'TORTA DE CHOCOLATE D26';
    case PRODUCTVARIANT_17 = 'TRES LECHES DOBLE CHOCOLATE D32x24';
    case PRODUCTVARIANT_18 = 'TORTA DE CHOCOLATE 32x24';
    case PRODUCTVARIANT_19 = 'TORTA TRES LECHES DE COCO 32x24';
    case PRODUCTVARIANT_20 = 'TORTA DE TRES LECHE DE FRESA D26';
    case PRODUCTVARIANT_21 = 'TORTA DE TRES LECHE DE FRESA D22';
    case PRODUCTVARIANT_22 = 'TORTA DE TRES LECHE DE FRESA 32x24';

    public function productId()
    {
        return match($this) {
            self::PRODUCTVARIANT_01 => 20,
            self::PRODUCTVARIANT_02 => 20,
            self::PRODUCTVARIANT_03 => 22,
            self::PRODUCTVARIANT_04 => 22,
            self::PRODUCTVARIANT_05 => 22,
            self::PRODUCTVARIANT_06 => 22,
            self::PRODUCTVARIANT_07 => 24,
            self::PRODUCTVARIANT_08 => 23,
            self::PRODUCTVARIANT_09 => 23,
            self::PRODUCTVARIANT_10 => 23,
            self::PRODUCTVARIANT_11 => 24,
            self::PRODUCTVARIANT_12 => 24,
            self::PRODUCTVARIANT_13 => 28,
            self::PRODUCTVARIANT_14 => 26,
            self::PRODUCTVARIANT_15 => 19,
            self::PRODUCTVARIANT_16 => 26,
            self::PRODUCTVARIANT_17 => 23,
            self::PRODUCTVARIANT_18 => 26,
            self::PRODUCTVARIANT_19 => 24,
            self::PRODUCTVARIANT_20 => 28,
            self::PRODUCTVARIANT_21 => 28,
            self::PRODUCTVARIANT_22 => 28,
        };
    }

    public function portions()
    {
        return match($this) {
            self::PRODUCTVARIANT_01 => '20 a 15 porciones',
            self::PRODUCTVARIANT_02 => '35 a 40 porciones',
            self::PRODUCTVARIANT_03 => '20 a 15 porciones',
            self::PRODUCTVARIANT_04 => '12 a 15 porciones',
            self::PRODUCTVARIANT_05 => '8 porciones',
            self::PRODUCTVARIANT_06 => '35 a 40 porciones',
            self::PRODUCTVARIANT_07 => '8 porciones',
            self::PRODUCTVARIANT_08 => '20 a 15 porciones',
            self::PRODUCTVARIANT_09 => '12 a 15 porciones',
            self::PRODUCTVARIANT_10 => '8 porciones',
            self::PRODUCTVARIANT_11 => '20 a 15 porciones',
            self::PRODUCTVARIANT_12 => '12 a 15 porciones',
            self::PRODUCTVARIANT_13 => '8 porciones',
            self::PRODUCTVARIANT_14 => '12 a 15 porciones',
            self::PRODUCTVARIANT_15 => '',
            self::PRODUCTVARIANT_16 => '20 a 15 porciones',
            self::PRODUCTVARIANT_17 => '35 a 40 porciones',
            self::PRODUCTVARIANT_18 => '35 a 40 porciones',
            self::PRODUCTVARIANT_19 => '35 a 40 porciones',
            self::PRODUCTVARIANT_20 => '20 a 15 porciones',
            self::PRODUCTVARIANT_21 => '12 a 15 porciones',
            self::PRODUCTVARIANT_22 => '35 a 40 porciones',
        };
    }

    public function sizeportion()
    {
        return match($this) {
            self::PRODUCTVARIANT_01 => 'Diámetro 26cm',
            self::PRODUCTVARIANT_02 => 'Diámetro 32cm x 24cm',
            self::PRODUCTVARIANT_03 => 'Diámetro 26cm',
            self::PRODUCTVARIANT_04 => 'Diámetro 22cm',
            self::PRODUCTVARIANT_05 => 'Diámetro 18cm',
            self::PRODUCTVARIANT_06 => 'Diámetro 32cm x 24cm',
            self::PRODUCTVARIANT_07 => 'Diámetro 18cm',
            self::PRODUCTVARIANT_08 => 'Diámetro 26cm',
            self::PRODUCTVARIANT_09 => 'Diámetro 22cm',
            self::PRODUCTVARIANT_10 => 'Diámetro 18cm',
            self::PRODUCTVARIANT_11 => 'Diámetro 26cm',
            self::PRODUCTVARIANT_12 => 'Diámetro 22cm',
            self::PRODUCTVARIANT_13 => 'Diámetro 18cm',
            self::PRODUCTVARIANT_14 => 'Diámetro 22cm',
            self::PRODUCTVARIANT_15 => '',
            self::PRODUCTVARIANT_16 => 'Diámetro 26cm',
            self::PRODUCTVARIANT_17 => 'Diámetro 32cm x 24cm',
            self::PRODUCTVARIANT_18 => 'Diámetro 32cm x 24cm',
            self::PRODUCTVARIANT_19 => 'Diámetro 32cm x 24cm',
            self::PRODUCTVARIANT_20 => 'Diámetro 26cm',
            self::PRODUCTVARIANT_21 => 'Diámetro 22cm',
            self::PRODUCTVARIANT_22 => 'Diámetro 32cm x 24cm',
        };
    }

    public function price()
    {
        return match($this) {
            self::PRODUCTVARIANT_01 => 10,
            self::PRODUCTVARIANT_02 => 15,
            self::PRODUCTVARIANT_03 => 20,
            self::PRODUCTVARIANT_04 => 25,
            self::PRODUCTVARIANT_05 => 30,
            self::PRODUCTVARIANT_06 => 35,
            self::PRODUCTVARIANT_07 => 40,
            self::PRODUCTVARIANT_08 => 45,
            self::PRODUCTVARIANT_09 => 20,
            self::PRODUCTVARIANT_10 => 25,
            self::PRODUCTVARIANT_11 => 30,
            self::PRODUCTVARIANT_12 => 35,
            self::PRODUCTVARIANT_13 => 40,
            self::PRODUCTVARIANT_14 => 45,
            self::PRODUCTVARIANT_15 => 20,
            self::PRODUCTVARIANT_16 => 25,
            self::PRODUCTVARIANT_17 => 30,
            self::PRODUCTVARIANT_18 => 35,
            self::PRODUCTVARIANT_19 => 40,
            self::PRODUCTVARIANT_20 => 45,
            self::PRODUCTVARIANT_21 => 20,
            self::PRODUCTVARIANT_22 => 25,
        };
    }

    public function hours()
    {
        return match($this) {
            self::PRODUCTVARIANT_01 => 48,
            self::PRODUCTVARIANT_02 => 48,
            self::PRODUCTVARIANT_03 => 48,
            self::PRODUCTVARIANT_04 => 48,
            self::PRODUCTVARIANT_05 => 24,
            self::PRODUCTVARIANT_06 => 24,
            self::PRODUCTVARIANT_07 => 24,
            self::PRODUCTVARIANT_08 => 24,
            self::PRODUCTVARIANT_09 => 72,
            self::PRODUCTVARIANT_10 => 72,
            self::PRODUCTVARIANT_11 => 72,
            self::PRODUCTVARIANT_12 => 48,
            self::PRODUCTVARIANT_13 => 48,
            self::PRODUCTVARIANT_14 => 24,
            self::PRODUCTVARIANT_15 => 24,
            self::PRODUCTVARIANT_16 => 24,
            self::PRODUCTVARIANT_17 => 72,
            self::PRODUCTVARIANT_18 => 24,
            self::PRODUCTVARIANT_19 => 24,
            self::PRODUCTVARIANT_20 => 48,
            self::PRODUCTVARIANT_21 => 48,
            self::PRODUCTVARIANT_22 => 24,
        };
    }

    public function image()
    {
        return match($this) {
            self::PRODUCTVARIANT_01 => 'product/torta_vitrina/TEW1.jpg',
            self::PRODUCTVARIANT_02 => 'product/torta_vitrina/TEW1.jpg',
            self::PRODUCTVARIANT_03 => 'product/torta_vitrina/TEW2.jpg',
            self::PRODUCTVARIANT_04 => 'product/torta_vitrina/TEW2.jpg',
            self::PRODUCTVARIANT_05 => 'product/torta_vitrina/TEW2.jpg',
            self::PRODUCTVARIANT_06 => 'product/torta_vitrina/TEW2.jpg',
            self::PRODUCTVARIANT_07 => 'product/torta_vitrina/TEW4.jpg',
            self::PRODUCTVARIANT_08 => 'product/torta_vitrina/TEW3.jpg',
            self::PRODUCTVARIANT_09 => 'product/torta_vitrina/TEW3.jpg',
            self::PRODUCTVARIANT_10 => 'product/torta_vitrina/TEW3.jpg',
            self::PRODUCTVARIANT_11 => 'product/torta_vitrina/TEW4.jpg',
            self::PRODUCTVARIANT_12 => 'product/torta_vitrina/TEW4.jpg',
            self::PRODUCTVARIANT_13 => 'product/torta_vitrina/TEW8.jpg',
            self::PRODUCTVARIANT_14 => 'product/torta_vitrina/TEW6.jpg',
            self::PRODUCTVARIANT_15 => 'product/postre/PT52.jpg',
            self::PRODUCTVARIANT_16 => 'product/torta_vitrina/TEW6.jpg',
            self::PRODUCTVARIANT_17 => 'product/torta_vitrina/TEW3.jpg',
            self::PRODUCTVARIANT_18 => 'product/torta_vitrina/TEW6.jpg',
            self::PRODUCTVARIANT_19 => 'product/torta_vitrina/TEW4.jpg',
            self::PRODUCTVARIANT_20 => 'product/torta_vitrina/TEW8.jpg',
            self::PRODUCTVARIANT_21 => 'product/torta_vitrina/TEW8.jpg',
            self::PRODUCTVARIANT_22 => 'product/torta_vitrina/TEW8.jpg',
        };
    }

    public function is_redemption()
    {
        return match($this) {
            self::PRODUCTVARIANT_01 => true,
            self::PRODUCTVARIANT_02 => true,
            self::PRODUCTVARIANT_03 => true,
            self::PRODUCTVARIANT_04 => true,
            self::PRODUCTVARIANT_05 => false,
            self::PRODUCTVARIANT_06 => false,
            self::PRODUCTVARIANT_07 => true,
            self::PRODUCTVARIANT_08 => false,
            self::PRODUCTVARIANT_09 => false,
            self::PRODUCTVARIANT_10 => false,
            self::PRODUCTVARIANT_11 => true,
            self::PRODUCTVARIANT_12 => true,
            self::PRODUCTVARIANT_13 => true,
            self::PRODUCTVARIANT_14 => false,
            self::PRODUCTVARIANT_15 => false,
            self::PRODUCTVARIANT_16 => false,
            self::PRODUCTVARIANT_17 => false,
            self::PRODUCTVARIANT_18 => false,
            self::PRODUCTVARIANT_19 => true,
            self::PRODUCTVARIANT_20 => true,
            self::PRODUCTVARIANT_21 => true,
            self::PRODUCTVARIANT_22 => true,
        };
    }
}
