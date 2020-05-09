<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameOccupation extends Model
{
    use GameCard;

    protected $table = 'game_occupations';
    protected $guarded = ['id'];
    protected $with = ['card'];

    public function card()
    {
        return $this->belongsTo('App\Card', 'card_id');
    }
}
