<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CardStatus;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pool_id');
            $table->integer('players_number');
            $table->integer('cards_number');
            $table->bigInteger('organizer_id');
            $table->timestamps();
        });
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->integer('player_order');
            $table->bigInteger('user_id');
        });
        Schema::create('game_pick_occupations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->integer('set_id');
            $table->string('card_id', 10);
        });
        Schema::create('game_pick_improvements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->integer('set_id');
            $table->string('card_id', 10);
        });
        Schema::create('game_occupations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->bigInteger('player_id');
            $table->string('card_id', 10);
            $table->enum('status', CardStatus::getValues());
            $table->timestamps();
        });
        Schema::create('game_improvements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->bigInteger('player_id');
            $table->string('card_id', 10);
            $table->enum('status', CardStatus::getValues());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('game_players');
        Schema::dropIfExists('game_pick_occupations');
        Schema::dropIfExists('game_pick_improvements');
        Schema::dropIfExists('game_occupations');
        Schema::dropIfExists('game_improvements');
    }
}
