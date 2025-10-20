<?php

use App\Models\Base\{
    Category,
    Designer,
    PricingScheme,
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('origin_code')->unique();
            $table->string('internal_code')->unique();
            $table->smallInteger('full_price')->unsigned();
            $table->text('description');
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Designer::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(PricingScheme::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('desired');
            $table->tinyInteger('sale_type');
            $table->string('sku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
