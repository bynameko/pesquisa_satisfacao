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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('invite_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('respondent_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->dateTime('submitted_at');

            $table->timestamps();

            $table->index('survey_id');
            $table->index('submitted_at');
            $table->text('user_agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
