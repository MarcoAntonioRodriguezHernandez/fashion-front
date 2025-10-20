<?php

namespace App\Enums;

enum ItemStatuses: int
{
    case AVAILABLE = 1;
    case ARCHIVED = 2;
    case DRY_CLEANING = 3;
    case TAILORING = 4;
    case TRANSFER = 5;
    case RENT = 6;
    case SOLD = 7;
    case IMPORTATION = 8;
    case LOST = 9;
    case TRAYECT = 10;

    public static function getAllNames()
    {
        return  [
            ItemStatuses::AVAILABLE->value => 'Disponible',
            ItemStatuses::ARCHIVED->value => 'Archivado',
            ItemStatuses::DRY_CLEANING->value => 'Tintorería',
            ItemStatuses::TAILORING->value => 'Sastrería',
            ItemStatuses::TRANSFER->value => 'Distribución',
            ItemStatuses::TRAYECT->value => 'Trayecto',
            ItemStatuses::RENT->value => 'Renta',
            ItemStatuses::SOLD->value => 'Venta',
            ItemStatuses::IMPORTATION->value => 'Importación', 
            ItemStatuses::LOST->value => 'Perdido',
        ];
    }

    public static function getAllColors() 
    {
        return [
            ItemStatuses::AVAILABLE->value => 'success',
            ItemStatuses::ARCHIVED->value => 'danger',
            ItemStatuses::DRY_CLEANING->value => 'bright-cyan',
            ItemStatuses::TAILORING->value => 'bright-orange',
            ItemStatuses::TRANSFER->value => 'warning',
            ItemStatuses::RENT->value => 'lima',
            ItemStatuses::SOLD->value => 'danger',
            ItemStatuses::IMPORTATION->value => 'blue',
            ItemStatuses::LOST->value => 'danger',
            ItemStatuses::TRAYECT->value => 'danger',
        ];
    }

    public static function getName(int $value)
    {
        return ItemStatuses::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getColor(int $value)
    {
        return ItemStatuses::getAllColors()[$value] ?? '#000000';
    }
}
