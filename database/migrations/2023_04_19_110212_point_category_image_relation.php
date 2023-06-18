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
        Schema::create('point_category_images', function (Blueprint $table) {
            $table->unsignedBigInteger('point_category_id');
            $table->unsignedBigInteger('image_id')->unique();
        });

        Schema::table('point_category_images', function (Blueprint $table) {

            $table->foreign('point_category_id')
                ->references('id')
                ->on('point_categories')
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
        //
    }
};
