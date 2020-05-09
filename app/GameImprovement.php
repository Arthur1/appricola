<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameImprovement extends Model
{
    use GameCard;

    protected $table = 'game_improvements';
    protected $guarded = ['id'];
    protected $with = ['card'];

    public function card()
    {
        return $this->belongsTo('App\Card', 'card_id');
    }
}
