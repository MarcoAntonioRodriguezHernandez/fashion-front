<?php

use app\Models\Base\Category;
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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('uses_amount')->nullable();
            $table->foreignIdFor(Category::class)->nullable()->constrained()->cascadeOnDelete();
            $table->tinyInteger('sale_type');
            $table->integer('min_products')->default(1);
            $table->integer('discount');
            $table->tinyInteger('coupon_type');
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
