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
        Schema::create('ntps', function (Blueprint $table) {
            $table->id();
            $table->string('rqm_code')->nullable()->unique();
            
            $table->foreignId('qualification_id')
                ->constrained('qualifications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('scholarship_program')->nullable();
            $table->integer('approve_slots')->nullable();
            $table->integer('total_amount')->nullable();
            $table->date('indicative_start_date')->nullable();
            $table->date('date_approve_by_tesda')->nullable();
            $table->date('date_received')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ntps');
    }
};
