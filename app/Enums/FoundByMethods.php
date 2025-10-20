<?php

namespace App\Enums;

enum FoundByMethods: int
{
    case UNSPECIFIED = 0;
    case RECOMMENDATION = 1;
    case FACEBOOK = 2;
    case INSTAGRAM = 3;
    case INTERNET = 4;
    case LOCATION = 5;
    case EXTEMPORARY = 6;

    public static function getAllNames()
    {
        return  [
            FoundByMethods::UNSPECIFIED->value => 'No especificado',
            FoundByMethods::RECOMMENDATION->value => 'Recomendación',
            FoundByMethods::FACEBOOK->value => 'Facebook',
            FoundByMethods::INSTAGRAM->value => 'Instagram',
            FoundByMethods::INTERNET->value => 'Internet',
            FoundByMethods::LOCATION->value => 'Ubicación',
            FoundByMethods::EXTEMPORARY->value => 'Extemporáneo',
        ];
    }

    public static function getName(int $value)
    {
        return FoundByMethods::getAllNames()[$value] ?? 'Desconocido';
    }
}
