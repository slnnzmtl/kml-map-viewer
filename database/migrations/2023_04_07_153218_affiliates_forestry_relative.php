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
        Schema::table('forestry', function (Blueprint $table) {
            $table->unsignedBigInteger('affiliate_id')->after('id');

            $table->foreign('affiliate_id')
                ->references('id')
                ->on('affiliates')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forestry', function (Blueprint $table) {
            $table->removeColumn('affiliate_id');
        });
    }
};
