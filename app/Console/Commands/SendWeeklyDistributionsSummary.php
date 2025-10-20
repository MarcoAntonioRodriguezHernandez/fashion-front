<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Base\Supply;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class SendWeeklyDistributionsSummary extends Command
{
    protected $signature = 'distributions:send-weekly-summary';
    protected $description = 'Envía una notificación semanal con el resumen de distribuciones automáticas.';

    public function handle()
    {
        $now = Carbon::now();
        $startOfWeek = $now->copy()->subWeek()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $endOfWeek = $now->copy()->subWeek()->endOfWeek(Carbon::SUNDAY)->endOfDay();

        $distributions = Supply::where('created_at', '>=', $startOfWeek)
            ->where('created_at', '<=', $endOfWeek)
            ->where('is_automatic', true)
            ->get();

        if ($distributions->isEmpty()) {
            $this->info('No hay distribuciones automáticas para notificar.');
            return 0;
        }

        $users = User::notifiedFor(\App\Enums\NotificationTypes::WARE_HOUSE)->get();

        $count = $distributions->count();
        $start = $startOfWeek->format('d/m/Y');
        $end = $endOfWeek->format('d/m/Y');
        $links = $distributions->map(function ($supply) {
            return [
                'code' => $supply->code,
                'url' => route('base.supply.show', $supply->id)
            ];
        });
        $text = json_encode([
            'resumen' => "Se realizaron $count distribuciones automáticas en la semana del $start al $end.",
            'links' => $links
        ]);
        $link = route('base.supply.index');
        $date = now()->toDateString();

        $firstSupply = $distributions->first();
        $orderMarketplaceId = null;
        if ($firstSupply) {
            $orderMarketplaceId = $firstSupply->order_marketplace_id ?? null;
        }

        $distributionsArray = $distributions->map(function ($supply) {
            return [
                'id' => $supply->id,
                'code' => $supply->code,
                'created_at' => $supply->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        foreach ($users as $user) {
            DB::table('notifications')->insert([
                'text' => $text,
                'link' => $link,
                'date' => $date,
                'order_marketplace_id' => $orderMarketplaceId,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->notify(new \App\Notifications\WeeklyDistributionsSummaryNotification(
                $distributionsArray,
                $start,
                $end
            ));
        }

        $this->info('Notificaciones semanales enviadas correctamente.');
        return 0;
    }
}
