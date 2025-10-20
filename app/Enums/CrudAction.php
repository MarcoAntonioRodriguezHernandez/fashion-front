<?php

namespace App\Enums;

enum CrudAction: string
{
    case GET = 'get';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
