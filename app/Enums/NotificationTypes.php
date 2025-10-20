<?php

namespace App\Enums;

enum NotificationTypes: string
{
    case ORDER_STATUS = 'os';
    case DAILY_REPORT = 'dr';
    case WARE_HOUSE = 'wh';

    public static function getAllNames()
    {
        return  [
            NotificationTypes::ORDER_STATUS->value => 'Estado de la orden',
            NotificationTypes::DAILY_REPORT->value => 'Reporte diario',
            NotificationTypes::WARE_HOUSE->value => 'Almacén',
        ];
    }
}
