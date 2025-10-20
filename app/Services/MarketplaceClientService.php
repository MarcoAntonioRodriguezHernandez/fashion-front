<?php

namespace App\Services;

use App\Models\Base\Marketplace;
use App\Services\ApiClient;
use Exception;
use Illuminate\Support\Facades\Log;

class MarketplaceClientService
{

    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function sendRequest(string $route, Marketplace $marketplace = null)
    {
        if (!config('services.marketplaces.dynamic_sync_enabled')) {
            throw new \Exception('Marketplace sync is disabled');
        }

        $marketplaces = $marketplace ? collect([$marketplace]) : $this->getSyncedMarketplaces();

        $responses = [];

        foreach ($marketplaces as $marketplace) {
            $url = $this->buildApiUrl($marketplace, $route);
            Log::info("Enviando request a: $url");

            try {
                $responses[$marketplace->slug] = $this->apiClient->get($url);
            } catch (\Throwable $e) {
                Log::error("Error al enviar request a {$marketplace->slug}: " . $e->getMessage());
                $responses[$marketplace->slug] = null;
            }
        }
        Log::info("Respuestas de los marketplaces: " . json_encode($responses));

        return $responses;
    }

    protected function buildApiUrl(Marketplace $marketplace, string $route = null)
    {
        return join('/', [
            trim(config('services.marketplaces.api_base_url'), '/'),
            $marketplace->slug,
            config('services.marketplaces.version'),
            trim($route, '/'),
        ]);
    }

    protected function getSyncedMarketplaces()
    {
        return Marketplace::where('sync_enabled', true)->get();
    }
}
