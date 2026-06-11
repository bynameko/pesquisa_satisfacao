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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->string('type', 20);

            $table->boolean('required')
                ->default(true);

            $table->integer('sort_order')
                ->default(0);

            $table->string('placeholder')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('survey_id');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
