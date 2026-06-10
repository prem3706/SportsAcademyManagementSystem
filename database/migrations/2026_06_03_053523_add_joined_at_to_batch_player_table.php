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
        if (!Schema::hasColumn('batch_player', 'joined_at')) {
            Schema::table('batch_player', function (Blueprint $table) {
                $table->timestamp('joined_at')
                    ->nullable()
                    ->after('player_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('batch_player', 'joined_at')) {
            Schema::table('batch_player', function (Blueprint $table) {
                $table->dropColumn('joined_at');
            });
        }
    }
};
