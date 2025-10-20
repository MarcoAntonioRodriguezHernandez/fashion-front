<?php

use App\Models\Base\Coupon;
use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupon_order_marketplace', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderMarketplace::class)->constrained('order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Coupon::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_order_marketplace');
    }
};
