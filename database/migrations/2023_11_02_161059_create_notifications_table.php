<?php

use App\Models\Marketplace\OrderMarketplace;
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('link');
            $table->date('date');
            $table->foreignIdFor(OrderMarketplace::class, 'order_marketplace_id')->constrained('order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
