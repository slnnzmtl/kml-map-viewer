<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('point_images', function (Blueprint $table) {
            $table->unsignedBigInteger('point_id');
            $table->unsignedBigInteger('image_id')->unique();
        });

        Schema::table('point_images', function (Blueprint $table) {
            $table->foreign('point_id')
                ->references('id')
                ->on('points')
                ->cascadeOnDelete();

            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_images', function (Blueprint $table) {

        });
    }
};
