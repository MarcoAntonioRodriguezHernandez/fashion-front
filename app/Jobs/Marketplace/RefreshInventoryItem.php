<?php

namespace App\Jobs\Marketplace;

use App\Services\Marketplace\RefreshProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{
    InteractsWithQueue,
    SerializesModels,
};
use Illuminate\Queue\Middleware\WithoutOverlapping;

class RefreshInventoryItem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $productSlug;
    public ?string $colorShadeId;

    private RefreshProductService $refreshProductService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $productSlug, string $colorShadeId = null)
    {
        $this->productSlug = $productSlug;
        $this->colorShadeId = $colorShadeId;

        $this->onQueue('marketplace-refresh');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->refreshProductService = new RefreshProductService();

        $responses = $this->refreshProductService->refreshInventoryItemMarketplace($this->productSlug, $this->colorShadeId);

        foreach ($responses as $response) {
            if ($response->failed()) {
                throw new \Exception(
                    'Failed to refresh inventory item [' . $this->productSlug . ']: ' .
                    'Status: ' . ($response->status() ?? 'N/A') . ' - ' .
                    'Error: ' . ($response->json('error') ?? $response->body() ?? 'Sin detalles')
                );
            }
        }
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->productSlug))->expireAfter(150)->shared()];
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->productSlug;
    }

}

