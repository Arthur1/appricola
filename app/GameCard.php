<?php

namespace App;

use App\Enums\CardStatus;

trait GameCard
{
    public function play()
    {
        $this->status = CardStatus::PLAYED;
        $this->save();
    }

    public function discard()
    {
        $this->status = CardStatus::DISCARDED;
        $this->save();
    }

    public function unplay()
    {
        $this->timestamps = false;
        $this->status = CardStatus::IN_HAND;
        $this->save();
    }

    public function faceUp()
    {
        $this->timestamps = false;
        $this->status = CardStatus::PLAYED;
        $this->save();
    }

    public function faceDown()
    {
        $this->timestamps = false;
        $this->status = CardStatus::TURNED_OVER;
        $this->save();
    }

    public function pass(int $player_id)
    {
        $this->player_id = $player_id;
        $this->status = CardStatus::IN_HAND;
        $this->save();
    }
}
