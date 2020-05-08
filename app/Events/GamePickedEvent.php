<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Game;
use App\GamePlayer;

class GamePickedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Game $game;
    private GamePlayer $player;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Game $game, GamePlayer $player)
    {
        $this->game = $game;
        $this->player = $player;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('game.' . $this->game->id);
    }

    public function broadcastWith()
    {
        return [
            'player_order' => $this->player->player_order,
            'next_player_order' => $this->player->player_order % $this->game->players_number + 1,
        ];
    }
}
