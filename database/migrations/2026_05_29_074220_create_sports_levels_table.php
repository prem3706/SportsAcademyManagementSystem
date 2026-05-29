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
        Schema::create('sports_levels', function (Blueprint $table) {

            $table->foreignId('sport_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('level_id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('fees', 10, 2);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_levels');
    }
};
