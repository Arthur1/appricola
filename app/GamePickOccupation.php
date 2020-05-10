<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CardStatus;
use App\GameOccupation;

class GamePickOccupation extends Model
{
    protected $table = 'game_pick_occupations';
    protected $guarded = ['id'];
    protected $with = ['card'];

    public function card()
    {
        return $this->belongsTo('App\Card', 'card_id');
    }

    public function pick(Game $game)
    {
        GameOccupation::create([
            'game_id' => $game->id,
            'player_id' => $game->my_player->id,
            'card_id' => $this->card_id,
            'status' => CardStatus::IN_HAND,
        ]);
        $this->delete();
    }

    public function discard(Game $game)
    {
        GameOccupation::create([
            'game_id' => $game->id,
            'player_id' => $game->my_player->id,
            'card_id' => $this->card_id,
            'status' => CardStatus::DISCARDED,
        ]);
        $this->delete();
    }

    public static function setCards(Game $game)
    {
        $hand_cards_number = $game->my_player->hand_occupations->count();
        if ($hand_cards_number === 7) {
            return [];
        }
        $set_id = (($game->my_player->player_order - $hand_cards_number - 1) % $game->players_number) + 1;
        if ($set_id <= 0) $set_id += $game->players_number;
        $pick_occupations = self::where('game_id', $game->id)->where('set_id', $set_id)->get();
        if ($hand_cards_number + $pick_occupations->count() !== $game->cards_number) {
            return [];
        }
        return $pick_occupations;
    }
}
