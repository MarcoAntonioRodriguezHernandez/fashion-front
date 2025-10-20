<?php

namespace App\Enums;

enum PaymentTypeVisibilities: int
{
    case VISIBLE = 1;
    case HIDDEN = 2;

    public static function getAllNames()
    {
        return  [
            PaymentTypeVisibilities::VISIBLE->value => 'Visible',
            PaymentTypeVisibilities::HIDDEN->value => 'Oculto',
        ];
    }

    public static function getName(int $value)
    {
        return PaymentTypeVisibilities::getAllNames()[$value] ?? 'Desconocido';
    }
}
