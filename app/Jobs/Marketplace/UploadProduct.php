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

class UploadProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $productSlug;

    private RefreshProductService $refreshProductService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $productSlug)
    {
        $this->productSlug = $productSlug;

        $this->onQueue('marketplace-refresh');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->refreshProductService = new RefreshProductService();

        $response = $this->refreshProductService->uploadProductMarketplace($this->productSlug);

        if (is_object($response) && method_exists($response, 'failed')) {
            if ($response->failed()) {
                $errorMessage = method_exists($response, 'getBody') ? $response->getBody() : 'Error desconocido';
                throw new \Exception('Failed to upload product [' . $this->productSlug . ']: ' . $errorMessage);
            }
        }
        elseif (is_array($response)) {
            if (isset($response['error']) || (isset($response['success']) && $response['success'] === false)) {
                $errorMessage = $response['error'] ?? $response['message'] ?? json_encode($response);
                throw new \Exception('Failed to upload product [' . $this->productSlug . ']: ' . $errorMessage);
            }
        }
        elseif (!$response) {
            throw new \Exception('Failed to upload product [' . $this->productSlug . ']: Respuesta vacía o inválida');
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
