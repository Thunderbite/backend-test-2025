<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->foreignId('prize_id')->nullable(); //id of the won prize
            $table->string('account'); //username of the user who played the game
            $table->dateTime('revealed_at')->nullable(); //timestamp in campaign's timezone
            // when the game has been played - it can be different than created_at
            $table->timestamps();

            $table->index('id', 'default_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
