<?php

namespace App\Console\Commands;

use App\Enums\StoreStatuses;
use App\Models\Base\{
    Item,
    Product,
    Store,
};
use App\Models\Support\StoreCard;
use App\Services\Item\ItemCardService;
use Error;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateMassCards extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:generate-mass-cards
        {--R|reset : If the generation should be started from 0}
        {--store= : The id of the store to be generated}
        {--products=* : The name of the products to be generated}
        {--size= : The size of page for card generation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate item cards for the given store. If no store selected, select the next non finished';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reset = $this->option('reset');
        $storeId = $this->option('store');
        $productNames = $this->option('products');
        $size = $this->option('size') ?? 30;

        $itemCardService = new ItemCardService();

        try {
            $storeCard = null;

            if (!empty($productNames)) {
                $items = $this->getItemsByProductNames($productNames, $storeId);
                $storeIds = $items->pluck('store_id')->unique()->values();

                do {
                    $storeCard = StoreCard::firstOrCreate([
                        'store_id' => $storeIds->shift(),
                    ], [
                        'amount_processed' => 0,
                    ]);
                } while ($storeCard->finished_at != null && $storeIds->isNotEmpty());

                $items = $items->filter(fn($i) => $i->store_id == $storeCard->store_id);
            } else if ($storeId) {
                $storeCard = StoreCard::firstOrCreate([
                    'store_id' => $storeId,
                ], [
                    'amount_processed' => 0,
                ]);

                $items = $this->getItemsByStore($storeId);
            } else {
                $storeCard = StoreCard::whereNull('finished_at')->first();

                if (!$storeCard) { // If no pending records, get the next new one
                    $storeCard = StoreCard::create([
                        'store_id' => Store::select('id')
                            ->where('status', StoreStatuses::ACTIVE)
                            ->whereNotIn('id', StoreCard::select('store_id')->pluck('store_id'))
                            ->firstOrFail()
                            ->id,
                    ]);
                }

                $items = $this->getItemsByStore($storeCard->store->id);
            }

            if ($reset && is_null($productNames)) { // Reset only if no product names are given
                $storeCard->update([
                    'amount_processed' => 0,
                    'finished_at' => null,
                ]);
            }

            if ($storeCard->finished_at) {
                throw new Exception('Store ' . $storeCard->store->slug . ' already finished');
            } else if ($items->isEmpty()) {
                throw new Exception('No items found for store ' . $storeCard->store->slug);
            }

            $itemIds = $items->pluck('id')
                ->skip($storeCard->amount_processed)
                ->take($size);

            $folder = storage_path('app/item_cards/' . $storeCard->store->slug);
            $range = ($storeCard->amount_processed ?: 0) . '_' . ($storeCard->amount_processed + $size);

            File::ensureDirectoryExists($folder);

            $zipPath = $folder . '/item_cards-' . $storeCard->store->slug . '-' . date('YmdHis') . '-' . $range . '.zip';

            $itemCardService->createFullCardFile($zipPath, $itemIds->toArray());

            $storeCard->increment('amount_processed', $size);

            if ($storeCard->amount_processed >= $items->count()) {
                $storeCard->update([
                    'finished_at' => now(),
                ]);

                $this->info('Store ' . $storeCard->store->slug . ' finished');
            } else {
                $this->info('Store ' . $storeCard->store->slug . ', ' . $size . ' items processed');
            }
        } catch (Exception $e) {
            $this->error('An exception has ocurred: ' . $e->getMessage());
            $itemCardService->logCardsInfo('An exception has ocurred: ' . $e->getMessage());
        } catch (Error $e) {
            $this->error('An error has ocurred: ' . $e->getMessage());
            $itemCardService->logCardsInfo('An error has ocurred: ' . $e->getMessage());
        }
    }

    private function getItemsByStore($storeId)
    {
        return Item::select('id')
            ->where('store_id', $storeId)
            ->get();
    }

    private function getItemsByProductNames(array $productNames, $storeId)
    {
        return Product::with('items')
            ->whereIn('name', $productNames)
            ->get()
            ->map(fn($p) => $p->items()
                ->when($storeId, fn($q) => $q->where('store_id', $storeId))
                ->orderBy('store_id')
                ->get())
            ->flatten(1);
    }
}
