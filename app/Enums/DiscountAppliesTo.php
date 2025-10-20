<?php

namespace App\Enums;

enum DiscountAppliesTo: int
{
    case NORMAL = 0;
    case SURCHARGE = 1;

    public static function getAllNames()
    {
        return  [
            DiscountAppliesTo::NORMAL->value => 'Normal',
            DiscountAppliesTo::SURCHARGE->value => 'Recargo',
        ];
    }

    public static function getName(int $value)
    {
        return DiscountAppliesTo::getAllNames()[$value] ?? 'Desconocido';
    }
}
