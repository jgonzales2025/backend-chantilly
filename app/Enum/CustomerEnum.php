<?php

namespace App\Enum;

enum CustomerEnum: string
{
    case CUSTOMER_01 = 'Walter';
    case CUSTOMER_02 = 'Erick';
    case CUSTOMER_03 = 'Joseph';

    public function lastname(){
        return match($this){
            self::CUSTOMER_01 => 'Jave',
            self::CUSTOMER_02 => 'Carrillo',
            self::CUSTOMER_03 => 'Gonzales',
        };
    }

    public function document_type()
    {
        return match($this){
            self::CUSTOMER_01 => 1,
            self::CUSTOMER_02 => 1,
            self::CUSTOMER_03 => 1,
        };
    }

    public function document_number()
    {
        return match($this){
            self::CUSTOMER_01 => '12345678',
            self::CUSTOMER_02 => '87654321',
            self::CUSTOMER_03 => '11223344',
        };
    }

    public function email()
    {
        return match($this){
            self::CUSTOMER_01 => 'walter@gmail.com',
            self::CUSTOMER_02 => 'erickcarrillo2603@gmail.com',
            self::CUSTOMER_03 => 'josephacuario20@gmail.com',
        };
    }

    public function password()
    {
        return match($this){
            self::CUSTOMER_01 => '12345',
            self::CUSTOMER_02 => '12345',
            self::CUSTOMER_03 => '12345',
        };
    }

    public function address()
    {
        return match($this){
            self::CUSTOMER_01 => '123 Main St',
            self::CUSTOMER_02 => '456 Elm St',
            self::CUSTOMER_03 => '789 Oak St',
        };
    }

    public function phone()
    {
        return match($this){
            self::CUSTOMER_01 => '984385774',
            self::CUSTOMER_02 => '903147057',
            self::CUSTOMER_03 => '927678054',
        };
    }

    public function department()
    {
        return match($this){
            self::CUSTOMER_01 => 'Lima',
            self::CUSTOMER_02 => 'Lima',
            self::CUSTOMER_03 => 'Lima',
        };
    }

    public function province()
    {
        return match($this){
            self::CUSTOMER_01 => 'Lima',
            self::CUSTOMER_02 => 'Lima',
            self::CUSTOMER_03 => 'Lima',
        };
    }

    public function district()
    {
        return match($this){
            self::CUSTOMER_01 => 'Miraflores',
            self::CUSTOMER_02 => 'Miraflores',
            self::CUSTOMER_03 => 'Miraflores',
        };
    }

    public function points()
    {
        return match($this){
            self::CUSTOMER_01 => 1000000,
            self::CUSTOMER_02 => 1000000,
            self::CUSTOMER_03 => 1000000,
        };
    }
}
