<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    protected $table = 'decks_master';
    protected $fillable = [];
    public $incrementing = false;
}
