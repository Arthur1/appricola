<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CardType;

class CreateCardsMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards_master', function (Blueprint $table) {
            $table->string('id', 10);
            $table->string('id_display', 10);
            $table->string('japanese_name');
            $table->string('deck_id', 10);
            $table->text('description');
            $table->enum('type', CardType::getValues());
            $table->integer('category');
            $table->string('prerequisite');
            $table->string('costs');
            $table->integer('card_points');
            $table->primary('id');
        });
        Schema::create('decks_master', function (Blueprint $table) {
            $table->string('id', 10);
            $table->string('name');
            $table->primary('id');
        });
        Schema::create('pools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('owner_id')->nullable();
        });
        Schema::create('pool_decks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pool_id');
            $table->string('deck_id');
            $table->unique(['pool_id', 'deck_id']);
        });
        Schema::create('pool_ban_cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pool_id');
            $table->string('card_id');
            $table->unique(['pool_id', 'card_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards_master');
        Schema::dropIfExists('decks_master');
        Schema::dropIfExists('pools');
        Schema::dropIfExists('pool_decks');
        Schema::dropIfExists('pool_ban_cards');
    }
}
