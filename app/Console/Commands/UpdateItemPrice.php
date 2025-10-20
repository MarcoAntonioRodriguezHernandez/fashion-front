<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Marketplace\ItemOrderMarketplace;

class UpdateItemPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:item-current-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update item_price in old records';

    /**
    * Execute the console command.
    */
    public function handle()
    {
        $this->info('Updating records...');

        ItemOrderMarketPlace::all()
            ->each(function($itemOrder) {
                $itemOrder->update([
                    'item_price' => $itemOrder->itemCurrentPrice,
                ]);
            });

        $this->info('Process completed.');
    }
}
