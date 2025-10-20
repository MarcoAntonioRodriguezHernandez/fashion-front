<?php

namespace App\Enums;

use App\Models\Base\{
    Item,
    Product,
};

enum ItemConditions: int
{
    case NEW = 1;
    case LIKE_NEW = 2;
    case PRE_LOVED = 3;
    case PRE_LIQUIDATION = 4;
    case LIQUIDATION = 5;
    case BAZAAR = 6;

    public static function getAllNames()
    {
        return  [
            ItemConditions::NEW->value => 'Nuevo',
            ItemConditions::LIKE_NEW->value => 'Como Nuevo',
            ItemConditions::PRE_LOVED->value => 'Preloved',
            ItemConditions::PRE_LIQUIDATION->value => 'Pre Liquidación',
            ItemConditions::LIQUIDATION->value => 'Liquidación',
            ItemConditions::BAZAAR->value => 'Bazar',
        ];
    }

    public static function getAllColors()
    {
        return [
            ItemConditions::NEW->value => 'bright-green',
            ItemConditions::LIKE_NEW->value => 'lima',
            ItemConditions::PRE_LOVED->value => 'bright-cyan',
            ItemConditions::PRE_LIQUIDATION->value => 'bright-yellow',
            ItemConditions::LIQUIDATION->value => 'bright-orange',
            ItemConditions::BAZAAR->value => 'bright-sky-blue',
        ];
    }

    public static function getAllPriceFunctions()
    {
        return  [
            ItemConditions::NEW->value => fn (Product $product, Item $item) => $product->pricingScheme->msrp,
            ItemConditions::LIKE_NEW->value => fn (Product $product, Item $item) => $item->price_sale,
            ItemConditions::PRE_LOVED->value => fn (Product $product, Item $item) => $item->price_sale,
            ItemConditions::PRE_LIQUIDATION->value => fn (Product $product, Item $item) => $item->price_sale,
            ItemConditions::LIQUIDATION->value => fn (Product $product, Item $item) => $product->pricingScheme->sku_4->price,
            ItemConditions::BAZAAR->value => fn (Product $product, Item $item) => $product->pricingScheme->sku_4->price,
        ];
    }

    public static function getName(int $value)
    {
        return ItemConditions::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getColor(int $value)
    {
        return ItemConditions::getAllColors()[$value] ?? 'secondary';
    }

    public static function getPriceFunction(int $value)
    {
        return ItemConditions::getAllPriceFunctions()[$value] ?? fn (Product $product, Item $item) => $item->price_sale;
    }
}
