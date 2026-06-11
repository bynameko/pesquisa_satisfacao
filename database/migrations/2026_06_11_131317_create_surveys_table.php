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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->enum('status', [
                'draft',
                'active',
                'closed'
            ])->default('draft');

            $table->boolean('anonymous')->default(false);

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();

            $table->text('thank_you_message')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
