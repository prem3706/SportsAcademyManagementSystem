<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run Migrations
     */
    public function up(): void
    {
        Schema::create('player_fees', function (Blueprint $table) {
            $table->id();
            
            // Player relation
            $table->foreignId('player_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Batch relation
            $table->foreignId('batch_id')
                ->nullable()
                ->constrained('batches')
                ->onDelete('cascade');

            $table->decimal('sub_totalamount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('penalty_amount', 10, 2)->default(0.00);
            $table->decimal('total_amt', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('payment_type', ['upi', 'cash', 'card']);
            $table->string('upi_id')->nullable();
            $table->string('img_upi')->nullable();
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse Migration
     */
    public function down(): void
    {
        Schema::dropIfExists('player_fees');
    }
};
