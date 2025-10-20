<?php

namespace App\Enums;

enum ItemIntegrities: int
{
    case OTHER = 0;
    case HEALTHY = 1;
    case BROKEN = 2;
    case MISSING_PARTS = 3;

    public static function getAllNames()
    {
        return [
            ItemIntegrities::OTHER->value => 'Otro',
            ItemIntegrities::HEALTHY->value => 'Buena condición',
            ItemIntegrities::BROKEN->value => 'Roto',
            ItemIntegrities::MISSING_PARTS->value => 'Partes faltantes',
        ];
    }

    public static function getName(int $value)
    {
        return ItemIntegrities::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getAllColors()
    {
        return [
            ItemIntegrities::OTHER->value => 'secondary',
            ItemIntegrities::HEALTHY->value => 'success',
            ItemIntegrities::BROKEN->value => 'danger',
            ItemIntegrities::MISSING_PARTS->value => 'info',
        ];
    }

    public static function getColor(int $value)
    {
        return ItemIntegrities::getAllColors()[$value] ?? 'secondary';
    }
}
