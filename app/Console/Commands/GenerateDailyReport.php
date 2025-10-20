<?php

namespace App\Console\Commands;

use App\Enums\NotificationTypes;
use App\Models\User;
use App\Notifications\DailyReportNotification;
use App\Services\Marketplace\IncomeReportService;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Console\Command;
use Illuminate\Support\{
    Carbon,
    Str,
};
use InvalidArgumentException;

class GenerateDailyReport extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:daily-report
        {--date= : The date of the report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a report of the orders of the day and send it to the email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date');

        $service = new IncomeReportService();

        try {
            $date = $date ? Carbon::parse($date) : now();

            $path = $service->createIncomeReportFile((object) [
                'start_date' => $date,
                'finish_date' => $date,
                'sale_type' => 0, // All
            ], 'daily-report-' . $date->format('Y-m-d') . '_' . date('YmdHis'));
        } catch (InvalidFormatException $e) {
            $this->error('Invalid date format. Use Y-m-d');
        } catch (InvalidArgumentException $e) {
            $this->error('No values to generate the report');
        }

        if (!isset($path) || !$path)
            return 1;

        $this->info('Find the report in ' . $path);

        $path = Str::of($path)->afterLast('/')->value();
        $path = route('marketplace.order_marketplace.income.resource', $path);

        User::query()
            ->notifiedFor(NotificationTypes::DAILY_REPORT)
            ->get()
            ->each(fn(User $user) => $user->notify(new DailyReportNotification($path, $date)));

        $this->info('The report has been sent to the users');
    }
}
