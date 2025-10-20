<?php

use App\Models\Base\Store;
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
        Schema::create('temporary_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->integer('uses_left')->default(1);
            $table->dateTime('expiration');
            $table->string('invitation_type');
            $table->foreignIdFor(Store::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_invitations');
    }
};
