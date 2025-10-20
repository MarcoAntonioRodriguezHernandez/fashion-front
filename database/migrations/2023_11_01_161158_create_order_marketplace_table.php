<?php

use App\Models\Base\{
    Event,
    Marketplace,
    Store,
};
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
        Schema::create('order_marketplace', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignIdFor(User::class, 'employee_id')->constrained('users')->cascadeOnUpdate();
            $table->foreignIdFor(User::class, 'client_id')->constrained('users')->cascadeOnUpdate();
            $table->foreignIdFor(Marketplace::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('amount');
            $table->integer('discount');
            $table->integer('amount_total');
            $table->foreignIdFor(Store::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('number_products');
            $table->tinyInteger('status');
            $table->dateTime('date_canceled')->nullable();
            $table->tinyInteger('found_by');
            $table->foreignIdFor(Event::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_marketplace');
    }
};
