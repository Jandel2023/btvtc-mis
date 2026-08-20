<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
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
        Schema::create('i_d_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')
            ->unique();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignId('qualification_id')
                ->constrained('qualifications')
                ->restrictOnDelete();
            $table->string('scholarship_program')->nullable();
            $table->string('user_role')->nullable();

            $table->date('application_date');
            $table->string('reason')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_d_applications');
    }
};
