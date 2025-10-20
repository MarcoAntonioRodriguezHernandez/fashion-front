<?php

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
        Schema::create('identity_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderMarketplace::class)->constrained('order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('front');
            $table->string('back');
            $table->longText('signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_documents');
    }
};
