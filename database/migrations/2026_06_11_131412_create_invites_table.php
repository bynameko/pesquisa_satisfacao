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
        Schema::create('invites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('token', 26)
                ->unique();

            $table->enum('status', [
                'pending',
                'answered',
                'expired'
            ])->default('pending');

            $table->string('generated_batch')
                ->nullable();

            $table->dateTime('responded_at')
                ->nullable();

            $table->timestamps();

            $table->index('survey_id');
            $table->index('status');
            $table->ipAddress('responded_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
