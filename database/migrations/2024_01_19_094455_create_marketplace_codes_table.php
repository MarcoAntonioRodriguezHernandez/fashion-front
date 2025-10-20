<?php

use App\Models\Base\Marketplace;
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
        Schema::create('marketplace_codes', function (Blueprint $table) {
            $table->id();
            $table->morphs('codable');
            $table->foreignIdFor(Marketplace::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_codes');
    }
};
