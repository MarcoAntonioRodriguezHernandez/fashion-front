<?php

use App\Models\Base\{
    ShippingPrice,
    UserAddress,
};
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
        Schema::create('order_shipping_marketplace', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ShippingPrice::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(OrderMarketplace::class)->constrained('order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(UserAddress::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipping_marketplace');
    }
};
