<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\Game;
use App\GamePlayer;
use App\Http\Requests\CreateGameRequest;
use App\Exceptions\OutOfCardsException;

class GameUtilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function list(Request $request)
    {
        return GamePlayer::getMyList()->toArray();
    }

    public function create(CreateGameRequest $request)
    {
        DB::transaction(function() use ($request) {
            $game_data = $request->only('players_number', 'pool_id', 'cards_number');
            $game_data['organizer_id'] = Auth::id();
            $game = Game::create($game_data);

            $user_ids = $request->users;
            shuffle($user_ids);
            foreach ($user_ids as $key => $user) {
                $user = User::where('name', '=', $user)->firstOrFail();
                $players[] = GamePlayer::create([
                    'game_id' => $game->id,
                    'player_order' => 1 + $key,
                    'user_id' => $user->id,
                ]);
            }

            $pile_occupations = $game->getPileOccupations();
            $pile_improvements = $game->getPileImprovements();
            if ($pile_occupations->count() < $game->players_number * $game->cards_number) {
                throw new OutOfCardsException();
            }
            if ($pile_improvements->count() < $game->players_number * $game->cards_number) {
                throw new OutOfCardsException();
            }

            foreach ($players as $player) {
                for ($i = 0; $i < $game->cards_number; $i++) {
                    $pick_occupation_rows[] = [
                        'game_id' => $game->id,
                        'set_id' => $player->player_order,
                        'card_id' => $pile_occupations->pop()->id,
                    ];
                    $pick_improvement_rows[] = [
                        'game_id' => $game->id,
                        'set_id' => $player->player_order,
                        'card_id' => $pile_improvements->pop()->id,
                    ];
                }
            }
            DB::table('game_pick_occupations')->insert($pick_occupation_rows);
            DB::table('game_pick_improvements')->insert($pick_improvement_rows);
        });
    }
}
