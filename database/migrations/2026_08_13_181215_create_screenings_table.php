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
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('mname')->nullable();
            $table->integer('aptitude_score');
            $table->integer('interview_score');
            $table->integer('total_score')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('batch_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('status')->nullable()
                 ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();;
            $table->string('address')->nullable();
            $table->date('date_screened')->nullable();
            $table->string('remarks')->nullable();
            $table->string('screened_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
