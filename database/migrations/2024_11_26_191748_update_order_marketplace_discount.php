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
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreignIdFor(OrderMarketplace::class)->after('id')->constrained('order_marketplace')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('reason')->after('order_marketplace_id');
            $table->integer('amount')->change();
            $table->dropColumn('name');
            $table->dropColumn('slug');
            $table->dropColumn('percentage');
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropForeign('discounts_order_marketplace_id_foreign');
            $table->dropColumn('order_marketplace_id');
            $table->dropColumn('reason');
            $table->integer('amount')->nullable()->change();
            $table->string('name')->after('id');
            $table->string('slug')->after('name')->unique();
            $table->integer('percentage')->after('slug')->nullable();
            $table->tinyInteger('status')->after('amount');
        });
    }
};
