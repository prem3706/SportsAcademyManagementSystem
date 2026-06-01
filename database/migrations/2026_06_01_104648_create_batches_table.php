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

            // Batch Name
            $table->string('name')->nullable();

            // Max Players
            $table->integer('capacity')->nullable();

            // Batch Timing
            $table->time('start_time')->nullable();

            $table->time('end_time')->nullable();

            // Sport Relation
            $table->foreignId('sport_id')
                ->constrained()
                ->onDelete('cascade')->nullable();

            // Level Relation
            $table->foreignId('level_id')
                ->constrained()
                ->onDelete('cascade')->nullable();

            // Status
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

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
