<?php

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
        Schema::table('item_order_marketplace', function (Blueprint $table) {
            $table->integer('item_price')->after('fitting_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_order_marketplace', function (Blueprint $table) {
            $table->dropColumn('item_price');
        });
    }
};
