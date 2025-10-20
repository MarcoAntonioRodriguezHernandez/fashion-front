<?php

namespace App\Enums\Auth;

enum ModuleAliases: string
{
    /**
     * If any alias es added, removed or modified, please update the resource as well (resources/js/src/Auth.js)
     */

    case USER = 'users';
    case PRODUCT = 'products';
    case ITEM = 'items';
    case VARIANT = 'variants';
    case CATEGORY = 'categories';
    case TAG = 'tags';
    case SIZE = 'sizes';
    case COLOR = 'colors';
    case STORE = 'stores';
    case DESIGNER = 'designers';
    case CHARACTERISTIC = 'characteristics';
    case PRICING_SCHEME = 'pricing-schemes';
    case SKU = 'skus';
    case MARKETPLACE = 'marketplaces';
    case PROVIDER = 'providers';
    case SUPPLY = 'supplies';
    case PAYMENT_TYPE = 'payment-types';
    case COUNTRY = 'countries';
    case INVOICE = 'invoices';
    case NOTIFICATION = 'notifications';
    case SHIPPING_PRICE = 'shipping-prices';
    case EVENT = 'events';
    case EVENT_TYPE = 'event_types';
    case ROLE = 'roles';
    case REPORT = 'reports';
    case ORDER = 'orders';
    case SUPER_ORDER = 'super-orders';

    public static function getName(int $value)
    {
        return PermissionTypes::tryFrom($value)?->value ?? 'Desconocido';
    }
}
