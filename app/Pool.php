<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Pool extends Model
{
    protected $table = 'pools';
    protected $guarded = ['id'];
    protected $with = ['ban_cards', 'decks'];
    public $timestamps = false;

    public function ban_cards()
    {
        return $this->hasMany('App\PoolBanCard', 'pool_id');
    }

    public function decks()
    {
        return $this->HasMany('App\PoolDeck', 'pool_id');
    }

    public static function getMyList()
    {
        return self::where('owner_id', Auth::id())->orWhereNull('owner_id')->get();
    }
}
