<?php

namespace App\Enums;

enum CategoryStatuses: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
    case NOT_VISIBLE= 2;

    public static function getAllNames()
    {
        return  [
            StoreStatuses::ACTIVE->value => 'Activo',
            StoreStatuses::INACTIVE->value => 'Inactivo',
            StoreStatuses::NOT_VISIBLE->value => 'No visible',

        ];
    }

    public static function getAllColors() 
    {
        return [
            self::ACTIVE->value => 'success',
            self::INACTIVE->value => 'warning',
            self::NOT_VISIBLE ->value => 'info',
        ];
    }

    public static function getName(int $value)
    {
        return self::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getColor(int $value)
    {
        return self::getAllColors()[$value] ?? 'secondary';
    }
}