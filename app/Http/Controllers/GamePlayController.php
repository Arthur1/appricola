<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Game;
use App\GamePickOccupation;
use App\GamePickImprovement;
use App\Exceptions\InvalidActionException;
use App\Events\GamePickedEvent;

class GamePlayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function states(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        if ($game->my_player) {
            $game->my_player->load(['hand_occupations', 'hand_improvements']);
            $game->my_player->pick_occupations = GamePickOccupation::setCards($game);
            $game->my_player->pick_improvements = GamePickImprovement::setCards($game);
        }
        return $game->toArray();
    }

    public function pickCards(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }

        $pick_occupations = GamePickOccupation::setCards($game);
        $pick_improvements = GamePickImprovement::setCards($game);
        $picked_occupation = $pick_occupations->firstWhere('id', $request->picked_occupation);
        $picked_improvement = $pick_improvements->firstWhere('id', $request->picked_improvement);
        if (is_null($picked_occupation) or is_null($picked_improvement)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $picked_occupation->pick($game);
        $picked_improvement->pick($game);

        broadcast(new GamePickedEvent($game, $game->my_player));
        return $this->states($request, $id);
    }
}
