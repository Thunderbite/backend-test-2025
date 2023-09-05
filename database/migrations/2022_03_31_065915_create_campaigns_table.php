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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('timezone'); //all dates are compared to this timezone
            $table->string('name');
            $table->string('slug');
            $table->timestamp('starts_at')->nullable(); //campaign can be used (games played on it) from this date onwards
            $table->timestamp('ends_at')->nullable(); //campaign can be used until this date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
