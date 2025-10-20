<?php

use App\Models\Base\Item;
use App\Models\Base\SupplyTransfer;
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
        Schema::create('supplied_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SupplyTransfer::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Item::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('delivered')->default(false);
            $table->tinyInteger('status');
            $table->tinyInteger('integrity')->nullable();
            $table->string('details')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplied_items');
    }
};
