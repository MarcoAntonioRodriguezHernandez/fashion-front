<?php

namespace App\Enums\DatabaseSync;

enum Methods: string
{
    case GET = 'get';
    case POST = 'post';
    case PATCH = 'patch';
    case DELETE = 'delete';
}
