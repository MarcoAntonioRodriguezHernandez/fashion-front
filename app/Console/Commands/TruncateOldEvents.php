<?php

namespace App\Console\Commands;

use App\Models\Base\Event;
use Exception;
use Illuminate\Console\Command;

class TruncateOldEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:truncate-old
        {--from= : The date from which the events should be truncated}
        {--to= : The date to which the events should be truncated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate old events from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $dateFrom = $this->option('from') ?? '1900-01-01';
            $dateTo = $this->option('to') ?? now()->subDays(30)->toDateString();

            $deletedCount = Event::query()
                ->where('date_start', '>=', $dateFrom)
                ->where('date_end', '<=', $dateTo)
                ->update([
                    'specification' => null,
                ]);

            $this->info('Deleted specification for ' . $deletedCount . ' events from ' . $dateFrom . ' to ' . $dateTo);
        } catch (Exception $e) {
            $this->error('An exception has ocurred: ' . $e->getMessage());
        }
    }
}
