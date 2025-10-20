<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\{
    Http,
    Log,
};

class DiscordBotService
{
    public static function errorLog(string $message, string $data, string $route)
    {
        try {
            Http::post(config('services.discordbot.endpoint'), [
                'projectName' => config('app.name'),
                'channelId' => config('services.discordbot.channel_id'),
                'token' => config('services.discordbot.token'),
                'color' => '#ff0000',
                'icon' => ':warning:',
                'title' => 'An error has ocurred',
                'description' => $message,
                'errorData' => [
                    'status' => 'error',
                    'data' => $data,
                    'timestamp' => date('Y-m-d\TH:i:s.v\Z'),
                    'route' => $route,
                ]
            ]);
        } catch (Exception $e) {
            Log::channel('fatal')->error('Could not send error to Discord Bot | ' . $e->getMessage());
        }
    }
}
