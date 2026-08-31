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
        Schema::table('batches', function (Blueprint $table) {
            $table->foreign('ntp_id')
                ->references('id')
                ->on('ntps')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('qualification_id')
                ->references('id')
                ->on('qualifications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        Schema::table('screenings', function (Blueprint $table) {
            $table->foreign('batch_id')
                ->references('id')
                ->on('batches')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::table('release_toolkits', function (Blueprint $table) {
            $table->foreign('batch_id')
                ->references('id')
                ->on('batches')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('screening_id')
                ->references('id')
                ->on('screenings')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('release_toolkits', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropForeign(['screening_id']);
        });

        Schema::table('screenings', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->dropForeign(['ntp_id']);
            $table->dropForeign(['qualification_id']);
        });
    }
};
