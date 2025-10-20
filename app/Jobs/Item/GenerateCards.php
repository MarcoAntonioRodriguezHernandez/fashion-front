<?php

namespace App\Jobs\Item;

use App\Services\Item\ItemCardService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{
    InteractsWithQueue,
    SerializesModels,
};
use Illuminate\Support\Facades\Storage;

class GenerateCards implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The items to generate cards for.
     */
    protected array $items;
    /**
     * The ID of the cards to generate.
     */
    protected string $token;

    /**
     * Create a new job instance.
     */
    public function __construct(array $items, string $token)
    {
        $this->items = $items;
        $this->token = $token;

        $this->onQueue('generate-cards');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $itemCardService = new ItemCardService();

        $cardFiles = $itemCardService->createFilesFromValues($this->items);
        $cardId = $itemCardService->createLogId();

        foreach ($cardFiles as $fileName => $file)
            $this->storeFile($file, $fileName, $cardId);
    }

    /**
     * Store the given file.
     */
    private function storeFile($file, string $fileName, string $suffix = '')
    {
        Storage::put('item_cards/' . $this->token . '/' . $fileName . '-' . $suffix . '.pdf', $file->output());
    }
}
