<?php

namespace App\Enums;

enum ProductSaleTypes: int
{
    case SALE = 1;
    case RENT = 2;

    public static function getAllNames()
    {
        return  [
            ProductSaleTypes::RENT->value => 'Renta y Venta',
            ProductSaleTypes::SALE->value => 'Exclusivo Venta',
        ];
    }
}
