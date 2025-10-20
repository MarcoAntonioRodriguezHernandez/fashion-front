<?php

namespace App\Services\Marketplace;

use App\Models\Base\Color;
use App\Services\MarketplaceClientService;
use App\Services\Support\JobChainBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class RefreshProductService extends MarketplaceClientService
{
    public function uploadProductMarketplace(string $productSlug)
    {
        Log::info("[uploadProductMarketplace] Slug: $productSlug");
        return $this->sendRequest('product/upload/' . $productSlug);
    }

    public function refreshProductMarketplace(string $productSlug, string $colorShadeId = null)
    {
        Log::info("[refreshProductMarketplace] Slug: $productSlug");
        return $this->sendRequest('product/refresh/' . $productSlug . '/' . $colorShadeId);
    }

    public function deleteProductMarketplace(string $productSlug, string $colorShadeId = null)
    {
        Log::info("[deleteProductMarketplace] Slug: $productSlug");
        return $this->sendRequest('product/delete/' . $productSlug . '/' . $colorShadeId);
    }

    public function refreshProductMediaMarketplace(string $productSlug, string $colorShadeId = null)
    {
        return $this->sendRequest('product-media/refresh/' . $productSlug . '/' . $colorShadeId);
    }

    public function refreshProductVariantMarketplace(string $productSlug, string $colorShadeId = null)
    {
        return $this->sendRequest('product-variant/refresh/' . $productSlug . '/' . $colorShadeId);
    }

    public function refreshInventoryItemMarketplace(string $productSlug, string $colorShadeId = null)
    {
        return $this->sendRequest('inventory-item/refresh/' . $productSlug . '/' . $colorShadeId);
    }

    public function chainRefreshFromColors(string $productSlug, Collection|array $colors, array $jobClasses): JobChainBuilder
    {
        $shades = $this->getShadesFromColors($colors);
        Log::info("[chainRefreshFromColors] Slug: $productSlug, Colores: " . json_encode($colors));
        Log::info("[chainRefreshFromColors] Tonos detectados: " . $shades->pluck('id')->join(', '));
        $jobChain = [];

        foreach ($shades as $shade) {
            foreach ($jobClasses as $jobClass) {
                $jobChain[] = new $jobClass($productSlug, $shade->id);
            }
        }

        return new JobChainBuilder($jobChain);
    }

    public function getShadesFromColors(Collection|array $colors): Collection
    {
        return collect($colors)->unique()
            ->map(fn($c) => is_numeric($c) ? Color::find($c) : $c)
            ->map(fn($c) => $c->parent_color_id ? $c->parentColor : $c)->unique('id');
    }
}
