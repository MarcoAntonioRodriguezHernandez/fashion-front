<?php

use App\Models\Marketplace\ItemOrderMarketplace;
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
        Schema::create('rent_detail_marketplace', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ItemOrderMarketplace::class)->constrained('item_order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('date_start');
            $table->date('date_end');
            $table->integer('insurance_price')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_detail_marketplace');
    }
};
