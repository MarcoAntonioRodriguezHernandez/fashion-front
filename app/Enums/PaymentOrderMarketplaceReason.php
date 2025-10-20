<?php

namespace App\Enums;

enum PaymentOrderMarketplaceReason: int
{
    case NORMAL = 0;
    case SURCHARGE = 1;

    public static function getAllNames()
    {
        return  [
            PaymentOrderMarketplaceReason::NORMAL->value => 'Normal',
            PaymentOrderMarketplaceReason::SURCHARGE->value => 'Recargo',
        ];
    }

    public static function getName(int $value)
    {
        return PaymentOrderMarketplaceReason::getAllNames()[$value] ?? 'Desconocido';
    }
}
