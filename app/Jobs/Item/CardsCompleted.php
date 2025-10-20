<?php

namespace App\Jobs\Item;

use App\Models\User;
use App\Notifications\CardsCompletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{
    InteractsWithQueue,
    SerializesModels,
};

class CardsCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $userId;
    private string $token;

    /**
     * Create a new job instance.
     */
    public function __construct(string $userId, string $token)
    {
        $this->userId = $userId;
        $this->token = $token;

        $this->onQueue('generate-cards');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::find($this->userId)->notify(new CardsCompletedNotification($this->token));
    }
}
