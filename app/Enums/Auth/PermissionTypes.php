<?php

namespace App\Enums\Auth;

enum PermissionTypes: int
{
    case READ = 1;
    case UPDATE = 2;
    case CREATE = 3;

    public static function getAllNames()
    {
        return  [
            PermissionTypes::READ->value => 'Lectura',
            PermissionTypes::UPDATE->value => 'Edición',
            PermissionTypes::CREATE->value => 'Creación',
        ];
    }

    public static function getAllColors()
    {
        return [
            PermissionTypes::READ->value => 'light-info',
            PermissionTypes::UPDATE->value => 'light-primary',
            PermissionTypes::CREATE->value => 'light-warning',
        ];
    }

    public static function tryFromAlias(int|string $value): static
    {
        return match ($value) {
            'r' => PermissionTypes::READ,
            'u' => PermissionTypes::UPDATE,
            'c' => PermissionTypes::CREATE,
            default => null,
        };
    }

    public static function getName(int $value)
    {
        return PermissionTypes::getAllNames()[$value] ?? 'Desconocido';
    }

    public static function getColor(int $value)
    {
        return PermissionTypes::getAllColors()[$value] ?? 'secondary';
    }
}
