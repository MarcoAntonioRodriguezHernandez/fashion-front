<?php

namespace App\Jobs\Marketplace;

use App\Services\Marketplace\RefreshProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\{
    InteractsWithQueue,
    SerializesModels,
};
use Illuminate\Queue\Middleware\WithoutOverlapping;

class RefreshProduct implements ShouldQueue
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

        $responses = $this->refreshProductService->refreshProductMarketplace($this->productSlug, $this->colorShadeId);

        foreach ($responses as $marketplaceSlug => $response) {
            // 🔎 Validación de tipo
            if (is_array($response)) {
                $status = $response['status'] ?? null;
                $mensaje = $response['message'] ?? 'Sin mensaje';

                if ($status !== 200) {
                    Log::error("🔥 Error en respuesta array de {$marketplaceSlug}: {$mensaje}", [
                        'response' => $response,
                    ]);
                    throw new \Exception("Error en marketplace {$marketplaceSlug}: {$mensaje}");
                }

                Log::info("✔ Respuesta válida (array) en {$marketplaceSlug} para producto {$this->productSlug}");
                continue;
            }

            // 🔎 Validación de objeto tipo HTTP
            if (is_object($response) && method_exists($response, 'failed')) {
                if ($response->failed()) {
                    Log::error("🔥 Fallo HTTP en {$marketplaceSlug}: " . $response->body());
                    throw new \Exception("Error refrescando producto en {$marketplaceSlug}: " . $response->body());
                }

                Log::info("✔ Refresco exitoso HTTP en {$marketplaceSlug} para producto {$this->productSlug}");
                continue;
            }

            // 🔴 Ninguna de las anteriores: respuesta inválida
            Log::info("❌ Respuesta desconocida de {$marketplaceSlug}", [
                'tipo' => gettype($response),
                'contenido' => $response,
            ]);
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
