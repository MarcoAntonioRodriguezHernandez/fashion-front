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

class RefreshProductVariant implements ShouldQueue
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

        $response = $this->refreshProductService->refreshProductVariantMarketplace($this->productSlug, $this->colorShadeId);

        if ($response->failed()) {
            throw new \Exception('Failed to refresh product variant [' . $this->productSlug . ']: ' . $response->getBody());
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
