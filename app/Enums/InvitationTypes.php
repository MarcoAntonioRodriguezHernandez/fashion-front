<?php

namespace App\Enums;

enum InvitationTypes: int
{
    case USER = 1;

    public static function getAllNames()
    {
        return  [
            InvitationTypes::USER->value => 'Usuario',
        ];
    }

    public static function getAllColors()
    {
        return [
            InvitationTypes::USER->value => 'success',
        ];
    }


    public static function getName(int $value)
    {
        return InvitationTypes::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getColor(int $value)
    {
        return InvitationTypes::getAllColors()[$value] ?? 'secondary';
    }
}
