<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations.
     */
    public function up(): void
    {
        Schema::create('batch_player', function (Blueprint $table) {

            // Batch Relation
            $table->foreignId('batch_id')
                ->constrained()
                ->onDelete('cascade');

            // Player Relation
            $table->foreignId('player_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Joined Date
            $table->date('joined_at')
                ->nullable();

        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_player');
    }
};
