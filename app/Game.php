<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Card;
use App\Enums\CardType;

class Game extends Model
{
    protected $table = 'games';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['players', 'pool', 'my_user'];

    public function players()
    {
        return $this->hasMany('App\GamePlayer', 'game_id');
    }

    public function my_user()
    {
        return $this->hasOne('App\GamePlayer', 'game_id')
            ->where('user_id', Auth::id());
    }

    public function pool()
    {
        return $this->belongsTo('App\Pool', 'pool_id');
    }

    public function all_occupations()
    {
        return $this->hasMany('App\GameOccupation', 'game_id');
    }

    public function all_improvements()
    {
        return $this->hasMany('App\GameImprovement', 'game_id');
    }

    public function getPileOccupations()
    {
        $this->loadMissing('all_occupations');
        $game_card_ids = $this->all_occupations->pluck('card_id')->all();
        $ban_card_ids = $this->pool->ban_cards->pluck('card_id')->all();
        $occupations = Card::whereIn('deck_id', $this->pool->decks->pluck('deck_id')->all())
            ->whereNotIn('id', array_merge($game_card_ids, $ban_card_ids))
            ->where('type', CardType::OCCUPATION)->get();
        return $occupations->shuffle();
    }

    public function getPileImprovements()
    {
        $this->loadMissing('all_improvements');
        $game_card_ids = $this->all_improvements->pluck('card_id')->all();
        $ban_card_ids = $this->pool->ban_cards->pluck('card_id')->all();
        $improvements = Card::whereIn('deck_id', $this->pool->decks->pluck('deck_id')->all())
            ->whereNotIn('id', array_merge($game_card_ids, $ban_card_ids))
            ->where('type', CardType::MINOR_IMPROVEMENT)->get();
        return $improvements->shuffle();
    }
}
