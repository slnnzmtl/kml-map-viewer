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
            $table->unsignedBigInteger('forestry_id')->after('category_id');

            $table->foreign('forestry_id')
                ->references('id')
                ->on('forestry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            $table->removeColumn('forestry_id');
        });
    }
};
