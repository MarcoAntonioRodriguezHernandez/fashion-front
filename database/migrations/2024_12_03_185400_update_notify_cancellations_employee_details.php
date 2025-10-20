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
        Schema::table('employee_details', function (Blueprint $table) {
            $table->renameColumn('notify_cancellations', 'notifications_allowed');
        });

        Schema::table('employee_details', function (Blueprint $table) {
            $table->string('notifications_allowed')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->tinyInteger('notifications_allowed')->default(false)->change();
        });

        Schema::table('employee_details', function (Blueprint $table) {
            $table->renameColumn('notifications_allowed', 'notify_cancellations');
        });
    }
};
