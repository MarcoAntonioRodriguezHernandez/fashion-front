<?php

namespace App\Enums;

enum CouponTypes: int
{
    case PERCENTAGE = 1;
    case FIXED = 2;

    public static function getAllNames()
    {
        return [
            CouponTypes::PERCENTAGE->value => 'Porcentaje',
            CouponTypes::FIXED->value => 'Fijo',
        ];
    }
}
