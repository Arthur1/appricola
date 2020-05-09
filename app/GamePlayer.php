<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Enums\CardStatus;

class GamePlayer extends Model
{
    protected $table = 'game_players';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $with = ['user', 'played_occupations', 'played_improvements'];

    public function game()
    {
        return $this->belongsTo('App\Game', 'game_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function played_occupations()
    {
        return $this->hasMany('App\GameOccupation', 'player_id')
            ->whereIn('status', [CardStatus::PLAYED, CardStatus::TURNED_OVER])
            ->orderBy('updated_at');
    }

    public function played_improvements()
    {
        return $this->hasMany('App\GameImprovement', 'player_id')
            ->whereIn('status', [CardStatus::PLAYED, CardStatus::TURNED_OVER])
            ->orderBy('updated_at');
    }

    public function hand_occupations()
    {
        return $this->hasMany('App\GameOccupation', 'player_id')
            ->where('status', CardStatus::IN_HAND);
    }

    public function hand_improvements()
    {
        return $this->hasMany('App\GameImprovement', 'player_id')
            ->where('status', CardStatus::IN_HAND);
    }

    public static function getMyList()
    {
        return self::where('user_id', Auth::id())->with('game')->get();
    }
}
