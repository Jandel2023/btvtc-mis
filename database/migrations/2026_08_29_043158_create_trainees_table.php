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
        Schema::create('trainees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('batch')
                 ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();;
            $table->string('name')
                 ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->boolean('enroll_status')->default(false)
                 ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('Active');
            $table->string('qr_code')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('picture')->nullable();
            $table->string('remarks')->nullable();
            $table->string('requirements')->nullable();
            $table->date('date_enrolled')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainees');
    }
};
