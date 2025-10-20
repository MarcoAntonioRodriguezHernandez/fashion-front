<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FormattableHandlerInterface;

class UserLogFormatter
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof FormattableHandlerInterface) {
                $user = auth()->user();
                $userLog = $user ? "$user->id: $user->full_name" : 'Guest';

                $handler->setFormatter(new LineFormatter("[%datetime%] %channel%.%level_name%: << $userLog >> %message% %context% %extra%\n"));
            }
        }
    }
}
