<?php

namespace App\Enums;

enum Genders: int
{
    case UNSPECIFIED = 0;
    case MALE = 1;
    case FEMALE = 2;

    public static function getAllNames()
    {
        return  [
            Genders::UNSPECIFIED->value => 'Sin especificar',
            Genders::MALE->value => 'Masculino',
            Genders::FEMALE->value => 'Femenino',
        ];
    }
}
