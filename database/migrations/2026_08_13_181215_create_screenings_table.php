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
        // data for screening
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('mname')->nullable();
            $table->integer('aptitude_score');
            $table->integer('interview_score');
            $table->integer('total_score')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('batch_id');
            $table->string('status')->nullable();
            $table->boolean('enrolled_status')->default(false)->nullable();
            $table->string('address')->nullable();
            $table->date('date_screened')->nullable();
            $table->string('screened_by')->nullable();
        // data for trainees
             $table->string('email')->nullable();
            $table->string('id_status')->default('Active');
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
        Schema::dropIfExists('screenings');
    }
};
