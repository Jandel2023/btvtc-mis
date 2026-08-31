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
        Schema::table('qualifications', function (Blueprint $table) {
            $table->foreign('qualification_level_id')
                ->references('id')
                ->on('qualification_levels')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('training_sector_id')
                ->references('id')
                ->on('training_sectors')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualifications', function (Blueprint $table) {
            $table->dropForeign(['qualification_level_id']);
            $table->dropForeign(['training_sector_id']);
        });
    }
};
