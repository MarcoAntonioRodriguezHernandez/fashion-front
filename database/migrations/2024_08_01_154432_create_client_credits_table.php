<?php

use App\Models\Base\ClientDetail;
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
        Schema::create('client_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ClientDetail::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('amount');
            $table->date('expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_credits');
    }
};
