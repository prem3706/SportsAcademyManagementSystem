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
        Schema::create('player_fees', function (Blueprint $table) {

            $table->id();

            // Fees Generate Relation
            $table->foreignId('fees_generate_id')
                ->constrained('fees_generates')
                ->onDelete('cascade');

            // Player Relation
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Sport Relation
            $table->foreignId('sport_id')
                ->constrained()
                ->onDelete('cascade');

            // Level Relation
            $table->foreignId('level_id')
                ->constrained()
                ->onDelete('cascade');

            // Fees Amount
            $table->decimal('amount', 10, 2);

            /*
            Status:
            unpaid
            paid
            */
            $table->enum('status', [
                'unpaid',
                'paid',
            ])->default('unpaid');

            // When Fees Generated
            $table->timestamp('generated_at')
                ->nullable();

            // When Player Paid
            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_fees');
    }
};
