<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CardStatus;
use App\GameImprovement;

class GamePickImprovement extends Model
{
    protected $table = 'game_pick_improvements';
    protected $guarded = ['id'];
    protected $with = ['card'];

    public function card()
    {
        return $this->belongsTo('App\Card', 'card_id');
    }

    public function pick(Game $game)
    {
        GameImprovement::create([
            'game_id' => $game->id,
            'player_id' => $game->my_player->id,
            'card_id' => $this->card_id,
            'status' => CardStatus::IN_HAND,
        ]);
        $this->delete();
    }

    public function discard(Game $game)
    {
        GameImprovement::create([
            'game_id' => $game->id,
            'player_id' => $game->my_player->id,
            'card_id' => $this->card_id,
            'status' => CardStatus::DISCARDED,
        ]);
        $this->delete();
    }

    public static function setCards(Game $game)
    {
        $hand_cards_number = $game->my_player->hand_improvements->count();
        if ($hand_cards_number === 7) {
            return [];
        }
        $set_id = (($hand_cards_number + $game->my_player->player_order - 1) % $game->players_number) + 1;
        $pick_improvements = self::where('game_id', $game->id)->where('set_id', $set_id)->get();
        if ($hand_cards_number + $pick_improvements->count() !== $game->cards_number) {
            return [];
        }
        return $pick_improvements;
    }
}
