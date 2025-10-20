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
        Schema::create('examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('example_type_id')->references('id')->on('example_types')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->longText('image');
            $table->longText('description');
            $table->dateTime('creation');
            $table->float('value');
            $table->integer('quantity');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examples');
    }
};
