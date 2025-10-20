<?php

use App\Models\Base\PaymentType;
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
        Schema::create('payment_order_marketplace', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderMarketplace::class)->constrained('order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->float('total');
            $table->float('payment');
            $table->foreignIdFor(PaymentType::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_order_marketplace');
    }
};
