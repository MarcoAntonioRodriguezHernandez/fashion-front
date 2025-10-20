<?php

namespace Database\Seeders\Base;

use App\Models\Base\{
    Product,
    ProductImage,
};
use Database\Data\ProductData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ProductData::getData();

        foreach ($data as $productData) {
            $info = collect($productData)->except('images')->toArray();

            [$info['title'], $info['name']] = explode(',', $info['full_name']);

            $info['name'] = trim($info['name']);
            $info['title'] = trim($info['title']);
            $info['slug'] = Str::slug($info['full_name']);
            unset($info['full_name']);

            $product = Product::factory()->create($info);

            foreach ($productData['images'] as $imageData) {
                ProductImage::factory()
                    ->for($product)
                    ->create($imageData);
            }
        }
    }
}
