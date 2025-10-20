<?php

namespace App\Traits\Base\Notification;

use App\Models\User;

trait HandlesUsers
{
    protected function findNotifiedUsers(string $search = null)
    {
        return User::search($search)
            ->with('employeeDetail')
            ->whereHas('employeeDetail', fn($q) => $q->whereNotNull('notifications_allowed'))
            ->get();
    }

    protected function findNonNotifiedUsers(string $search = null)
    {
        return User::search($search)
            ->with('employeeDetail')
            ->whereHas('employeeDetail', fn($q) => $q->whereNull('notifications_allowed'))
            ->get();
    }
}
