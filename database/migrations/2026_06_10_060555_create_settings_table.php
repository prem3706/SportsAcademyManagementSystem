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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('allow_penalty')->default(false);
            $table->integer('penalty_days')->default(0);
            $table->string('penalty_type')->default('fixed'); // fixed or percentage
            $table->decimal('penalty_amount', 10, 2)->default(0.00);
            $table->string('discount_type')->default('percentage');
            $table->decimal('discount_monthly', 10, 2)->default(0.00);
            $table->decimal('discount_quarterly', 10, 2)->default(0.00);
            $table->decimal('discount_half_yearly', 10, 2)->default(0.00);
            $table->decimal('discount_yearly', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
