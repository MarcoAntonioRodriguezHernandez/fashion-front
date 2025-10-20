<?php

use App\Models\Base\Item;
use App\Models\Marketplace\OrderMarketplace;
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
        Schema::create('item_order_marketplace', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(OrderMarketplace::class)->constrained('order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('additional_notes')->nullable();
            $table->integer('fitting_price')->nullable();
            $table->string('fitting_notes')->nullable();
            $table->tinyInteger('sale_type');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_order_marketplace');
    }
};
