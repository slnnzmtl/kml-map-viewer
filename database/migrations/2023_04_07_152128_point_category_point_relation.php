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
        Schema::table('points', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('id');

            $table->foreign('category_id')
                ->references('id')
                ->on('point_categories')
                ->onDelete('CASCADE');

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
