<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Common\BaseSyncController;
use App\Models\Base\{
    Category,
    PricingScheme,
    Sku,
};
use Exception;
use Illuminate\Support\{
    Collection,
    Str,
};

class PricingSchemeSyncController extends BaseSyncController
{

    public function __construct()
    {
        parent::__construct(config('services.conspiracy.api_base_url') . '/db-sync/pricing-scheme');
    }


    /**
     * Process the data fetched from the remote
     * 
     * @param Collection $data The fetched data to sync
     */
    protected function processData(Collection $data)
    {
        $skus = Sku::select('id', 'price', 'duration')
            ->get()
            ->mapToGroups(fn (Sku $sku) => ['sku_' . $sku['duration'] => $sku])
            ->map(fn (Collection $group) => $group->keyBy('price'));
        $categories = Category::select('id', 'slug')->get()->keyBy('slug');

        foreach ($data as $schemeData) {
            try {
                $sku4 = $skus->get('sku_4')->get($schemeData['sku_4']);
                $sku8 = $skus->get('sku_8')->get($schemeData['sku_8']);

                if (!$sku4) {
                    $sku4 = Sku::create([
                        'sku' => fake()->unique()->numerify('##########'),
                        'name' => 'Sku created from price ' . $schemeData['sku_4'],
                        'description' => 'Sku created from price ' . $schemeData['sku_4'],
                        'duration' => 4,
                        'price' => $schemeData['sku_4'],
                    ]);
                }

                if (!$sku8) {
                    $sku8 = Sku::create([
                        'sku' => fake()->unique()->numerify('##########'),
                        'name' => 'Sku created from price ' . $schemeData['sku_8'],
                        'description' => 'Sku created from price ' . $schemeData['sku_8'],
                        'duration' => 8,
                        'price' => $schemeData['sku_8'],
                    ]);
                }

                PricingScheme::create([
                    'sku_4_id' => $sku4->id,
                    'sku_8_id' => $sku8->id,
                    'msrp' => $schemeData['msrp'],
                    'increase' => $schemeData['increase'],
                    'category_id' => $categories->get($schemeData['category'])->id,
                ]);
            } catch (Exception $e) {
                $this->errors[] = $schemeData['msrp'] . ': ' . $e->getMessage();
            }
        }
    }
}
