<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Game;
use App\GamePickOccupation;
use App\GamePickImprovement;

class GamePlayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function states(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        if ($game->my_user) {
            $game->my_user->load(['hand_occupations', 'hand_improvements']);
            $game->my_user->pick_occupations = GamePickOccupation::setCards($game);
            $game->my_user->pick_improvements = GamePickImprovement::setCards($game);
        }
        return $game->toArray();
    }
}
