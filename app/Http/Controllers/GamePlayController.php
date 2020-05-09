<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Game;
use App\GamePickOccupation;
use App\GamePickImprovement;
use App\Exceptions\InvalidActionException;
use App\Events\GamePickedEvent;
use App\Events\GameUpdateEvent;

class GamePlayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function states(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        $game->is_picking = $game->is_picking();
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
        $game->my_player->load(['hand_occupations', 'hand_improvements']);
        if ($game->my_player->hand_occupations->count() >= 7) {
            throw new InvalidActionException('無効な行動です');
        }
        DB::transaction(function() use ($game, $request) {
            $pick_occupations = GamePickOccupation::setCards($game);
            $pick_improvements = GamePickImprovement::setCards($game);
            $picked_occupation = $pick_occupations->firstWhere('id', $request->picked_occupation);
            $picked_improvement = $pick_improvements->firstWhere('id', $request->picked_improvement);
            if (is_null($picked_occupation) or is_null($picked_improvement)) {
                throw new InvalidActionException('そのカードは存在しません');
            }

            $picked_occupation->pick($game);
            $picked_improvement->pick($game);

            if ($game->my_player->hand_occupations()->get()->count() === 7) {
                $discard_occupations = $pick_occupations->where('id', '!=', $request->picked_occupation);
                $discard_improvements = $pick_improvements->where('id', '!=', $request->picked_improvement);
                foreach ($discard_occupations as $occupation) {
                    $occupation->discard($game);
                }
                foreach ($discard_improvements as $improvement) {
                    $improvement->discard($game);
                }
            }
        });

        $game->refresh();
        $game->is_picking = $game->is_picking();
        $game->my_player->pick_occupations = GamePickOccupation::setCards($game);
        $game->my_player->pick_improvements = GamePickImprovement::setCards($game);

        if ($game->is_picking) {
            broadcast(new GamePickedEvent($game, $game->my_player));
        } else {
            broadcast(new GameUpdateEvent($game));
        }

        return $game->toArray();
    }
}
