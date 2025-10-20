<?php

namespace App\Enums;

enum OrderSaleType: int
{
    case SALE = 1;
    case RENT = 2;

    public static function getAllNames()
    {
        return  [
            OrderSaleType::RENT->value => 'Renta',
            OrderSaleType::SALE->value => 'Venta',
        ];
    }

    public static function getAllCodes()
    {
        return  [
            OrderSaleType::RENT->value => 'rent',
            OrderSaleType::SALE->value => 'sale',
        ];
    }

    public static function getName(int $value)
    {
        return OrderSaleType::getAllNames()[$value] ?? 'Desconocido';
    }
}
