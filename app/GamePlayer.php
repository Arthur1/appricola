<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class GamePlayer extends Model
{
    protected $table = 'game_players';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $with = ['user'];

    public function game()
    {
        return $this->belongsTo('App\Game', 'game_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function getMyList()
    {
        return self::where('user_id', Auth::id())->with('game')->get();
    }
}
