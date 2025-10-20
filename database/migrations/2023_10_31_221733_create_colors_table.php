<?php

use App\Models\Base\Color;
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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('hexadecimal')->default('#FFFFFF');
            $table->string('texture_src')->nullable();
            $table->foreignIdFor(Color::class, 'parent_color_id')->nullable()->constrained('colors')->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
