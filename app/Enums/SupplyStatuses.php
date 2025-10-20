<?php

namespace App\Enums;

enum SupplyStatuses: int
{
    case PENDING = 1;
    case SENT = 2;
    case ERROR = 3;
    case INCOMPLETE = 4;
    case COMPLETE = 5;
    case REDISTRIBUTION = 6;

    public static function getAllNames()
    {
        return [
            self::PENDING->value => 'Pendiente',
            self::SENT->value => 'Enviado',
            self::ERROR->value => 'Error',
            self::INCOMPLETE->value => 'Incompleto',
            self::COMPLETE->value => 'Completo',
            self::REDISTRIBUTION->value => 'Redistribución',
        ];
    }

    public static function getAllColors()
    {
        return [
            self::PENDING->value => 'warning',
            self::SENT->value =>  'success',
            self::ERROR->value =>  'danger',
            self::INCOMPLETE->value => 'info',
            self::COMPLETE->value => 'success',
            self::REDISTRIBUTION->value => 'warning',
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
