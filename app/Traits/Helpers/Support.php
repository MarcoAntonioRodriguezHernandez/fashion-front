<?php

namespace App\Traits\Helpers;

use Illuminate\Support\{
    Collection,
    ItemNotFoundException,
    Str,
};

trait Support
{

    /**
     * Get the full list of ids of models
     *
     * If any of the values is not numeric, create a model with only that name and slug
     *
     * All values non numeric and not strings are ignored
     *
     * @param Collection $models Values to find or create
     * @param string $className The class to create or find
     * @param array $extraData Data to add to the result. It should be an associative array
     *
     * @return Collection
     */
    private function getModelIds(Collection $models, string $className, array $extraData = [])
    {
        $modelNames = $models->filter(fn ($val) => is_string($val) && !is_numeric($val)); // Get only string non numeric values
        $modelIds = $models->filter(fn ($val) => is_numeric($val)); // Get only numeric values

        foreach ($modelNames as $name) {
            $modelEntity = app($className)->create([
                'name' => $name,
                'slug' => Str::slug($name),
                ...$extraData,
            ]);

            $modelIds = $modelIds->push($modelEntity->id);
        }

        return $modelIds;
    }

    public static function generateVariantCode(string $size, string $color): string
    {
        return Str::slug($size) . '-' . Str::slug($color);
    }

    /**
     * Get a value from a collection, or throw an exception with the appropriate name
     * 
     * @param Collection $src Source collection
     * @param mixed $key The target value
     * @param string $name Name to be shown in the exception
     * @param string $random Return a random value from the collection if key not found
     * 
     * @return mixed
     */
    private function getValue(Collection $src, mixed $key, string $name = 'item', bool $random = false)
    {
        if ($src->has($key)) {
            return $src->get($key);
        } else if ($random) {
            return $src->random();
        } else {
            throw new ItemNotFoundException('The requested ' . $name . ' (' . $key . ') could not be found');
        }
    }
}
