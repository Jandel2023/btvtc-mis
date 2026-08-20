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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code')->unique();

            $table->foreignId('ntp_id')
                  ->nullable()
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

            $table->foreignId('qualification_id')
                ->constrained('qualifications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            
            $table->string('scholarship_program')->nullable();
            $table->string('batch_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->string('schedule')->nullable();
            $table->string('venue')->nullable();
            $table->string('status')->default('Upcoming');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
