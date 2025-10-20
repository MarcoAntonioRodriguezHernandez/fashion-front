<?php

use App\Enums\ItemConditions;
use App\Models\Base\{
    Invoice,
    ProductVariant,
    Store,
};
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProductVariant::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Store::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('serial_number')->unique();
            $table->string('barcode')->unique()->nullable();
            $table->float('price_sale');
            $table->float('price_purchase');
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->tinyInteger('status');
            $table->tinyInteger('condition');
            $table->tinyInteger('integrity');
            $table->boolean('is_new')->default(ItemConditions::NEW);
            $table->text('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
