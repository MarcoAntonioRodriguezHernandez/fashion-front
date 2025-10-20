<?php

use App\Models\Base\{
    Sku,
    Category,
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
        Schema::create('pricing_schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Sku::class, 'sku_4_id')->constrained('skus')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Sku::class, 'sku_8_id')->constrained('skus')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('msrp');
            $table->tinyInteger('increase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_schemes');
    }
};
