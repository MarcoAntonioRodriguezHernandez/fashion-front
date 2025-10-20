<?php

namespace App\Enums;

enum OrderStatuses: int
{
    case TO_VALIDATE = 1;
    case PAY = 2;
    case WAITING_FOR_COLLECTION = 3;
    case RECEIVED_IN_STORE = 4;
    case DELIVERED_TO_CUSTOMER = 5;
    case DELIVERED_BY_CUSTOMER = 6;
    case CLOSED = 7;
    case CANCELLED = 8;
    case RETURNED = 9;

    public static function getAllNames()
    {
        return  [
            OrderStatuses::TO_VALIDATE->value => 'Por validar',
            OrderStatuses::PAY->value => 'Pagada',
            OrderStatuses::WAITING_FOR_COLLECTION->value => 'En espera de recolección',
            OrderStatuses::RECEIVED_IN_STORE->value => 'Recibido en tienda',
            OrderStatuses::DELIVERED_TO_CUSTOMER->value => 'Entregado al cliente',
            OrderStatuses::DELIVERED_BY_CUSTOMER->value => 'Entregado por cliente',
            OrderStatuses::CLOSED->value => 'Cerrada',
            OrderStatuses::CANCELLED->value => 'Cancelada',
            OrderStatuses::RETURNED->value => 'Devolución',
        ];
    }
    public static function getAllColors()
    {
        return [
            OrderStatuses::TO_VALIDATE->value => 'info',
            OrderStatuses::PAY->value => 'success',
            OrderStatuses::WAITING_FOR_COLLECTION->value => 'warning',
            OrderStatuses::RECEIVED_IN_STORE->value => 'success',
            OrderStatuses::DELIVERED_TO_CUSTOMER->value => 'warning',
            OrderStatuses::DELIVERED_BY_CUSTOMER->value => 'success',
            OrderStatuses::CLOSED->value => 'dark',
            OrderStatuses::CANCELLED->value => 'danger',
            OrderStatuses::RETURNED->value => 'danger',
        ];
    }

    public static function getName(int $value)
    {
        return OrderStatuses::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getColor(int $value)
    {
        return OrderStatuses::getAllColors()[$value] ?? 'secondary';
    }

    public static function getCancellationTypes(): array
    {
        return [
            self::CANCELLED->value => 'Total',
            self::RETURNED->value => 'Parcial',
        ];
    }

    public static function getNotificationMessage(): array
    {
        return [
            self::TO_VALIDATE->value => 'Se ha creado un nuevo pedido',
            self::PAY->value => '',
            self::WAITING_FOR_COLLECTION->value => '',
            self::DELIVERED_TO_CUSTOMER->value => '',
            self::DELIVERED_BY_CUSTOMER->value => '',
            self::CLOSED->value => '',
            self::CANCELLED->value => 'Se ha cancelado el pedido',
            self::RETURNED->value => 'Se ha devuelto el pedido',
        ];
    }
}
