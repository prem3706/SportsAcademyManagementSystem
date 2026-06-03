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
        Schema::create('fees_generates', function (Blueprint $table) {

            $table->id();

            // Month Example: june
            $table->string('month');

            // Year Example: 2026
            $table->year('year');

            /*
            Status:
            unpaid
            partial
            paid
            */
            $table->enum('status', [
                'unpaid',
                'partial',
                'paid',
            ])->default('unpaid');

            // Admin User
            $table->foreignId('generated_by')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_generates');
    }
};
