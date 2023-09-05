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
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('level'); //low, med, high
            $table->decimal('weight', 10, 2)->nullable(); // 0.01 - 99.99, determines the chance of winning
            $table->timestamp('starts_at')->nullable(); //prize can be won from this date onwards
            $table->timestamp('ends_at')->nullable(); //until this date
            $table->timestamps();

            $table->index(['name', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
