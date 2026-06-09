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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->string('home_team');
            $table->string('home_team_flag_url')->nullable();
            $table->string('away_team');
            $table->string('away_team_flag_url')->nullable();
            $table->dateTime('match_time');
            $table->string('stage')->nullable();
            $table->string('group')->nullable();
            $table->string('venue')->nullable();
            $table->string('status')->default('upcoming');
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->string('stream_provider')->nullable();
            $table->string('owncast_url')->nullable();
            $table->boolean('owncast_chat_enabled')->default(false);
            $table->timestamps();

            $table->index('status');
            $table->index('match_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
