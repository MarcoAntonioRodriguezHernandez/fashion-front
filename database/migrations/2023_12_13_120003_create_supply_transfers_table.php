<?php

use App\Models\Base\Store;
use App\Models\Base\Supply;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supply_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Supply::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'recipient_id')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->datetime('reception_date')->nullable();
            $table->foreignIdFor(Store::class, 'origin_id')->constrained('stores')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Store::class, 'destination_id')->constrained('stores')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_transfers');
    }
};
