<?php

namespace App\Enums;

enum PaymentStatuses: int
{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;

    public static function getAllNames(): array
    {
        return [
            self::PENDING->value => 'Pendiente',
            self::APPROVED->value => 'Pagado',
            self::REJECTED->value => 'Rechazado',
        ];
    }

    public static function getAllColors(): array
    {
        return [
            self::PENDING->value => 'warning',
            self::APPROVED->value => 'success',
            self::REJECTED->value => 'danger',
        ];
    }

    public static function getName(int $value): string
    {
        return self::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getColor(int $value): string
    {
        return self::getAllColors()[$value] ?? 'secondary';
    }
}