<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards_master';
    protected $fillable = [];
    protected $with = ['deck'];
    public $incrementing = false;

    public function deck()
    {
        return $this->belongsTo('App\Deck', 'deck_id');
    }
}
