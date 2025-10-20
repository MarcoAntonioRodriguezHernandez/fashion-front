<?php

namespace App\Enums\Auth;
enum RoleAliases: string
{
    case SUPER_ADMIN = 'super-administrador';
    case INITIAL = 'inicial';
    case INVENTORY = 'inventariado';
    case ADMIN_ORDERS = 'admin-ordenes';
}
