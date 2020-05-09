<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Game;
use App\GamePickOccupation;
use App\GamePickImprovement;
use App\Exceptions\InvalidActionException;
use App\Exceptions\OutOfCardsException;
use App\Events\GamePickedEvent;
use App\Events\GameUpdateEvent;
use App\Http\Requests\DrawCardsRequest;
use App\Enums\CardStatus;

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
            $game->my_player->load(['hand_occupations', 'hand_improvements', 'discarded_occupations', 'discarded_improvements']);
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
        $game->my_player->load(['hand_occupations', 'hand_improvements', 'discarded_occupations', 'discarded_improvements']);
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
            broadcast(new GameUpdateEvent($game, 'ドラフトが終了しました'));
        }

        return $game->toArray();
    }

    public function playOccupation(Request $request, $game_id, $occupation_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $game->my_player->load('hand_occupations');
        $play_occupation = $game->my_player->hand_occupations->firstWhere('id', $occupation_id);
        if (is_null($play_occupation)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $play_occupation->play();

        $message = $game->my_player->user->name . '(' . $game->my_player->player_order . ')が【'
            . $play_occupation->card->japanese_name . '】をプレイしました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function playImprovement(Request $request, $game_id, $improvement_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $game->my_player->load('hand_improvements');
        $play_improvement = $game->my_player->hand_improvements->firstWhere('id', $improvement_id);
        if (is_null($play_improvement)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $play_improvement->play();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が【'
            . $play_improvement->card->japanese_name . '】をプレイしました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function unplayOccupation(Request $request, $game_id, $occupation_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $game->my_player->load('all_occupations');
        $unplay_occupation = $game->my_player->all_occupations->firstWhere('id', $occupation_id);
        if (is_null($unplay_occupation)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $unplay_occupation->unplay();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が【'
            . $unplay_occupation->card->japanese_name . '】を手札に戻しました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function unplayImprovement(Request $request, $game_id, $improvement_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $game->my_player->load('all_improvements');
        $unplay_improvement = $game->my_player->all_improvements->firstWhere('id', $improvement_id);
        if (is_null($unplay_improvement)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $unplay_improvement->unplay();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が【'
            . $unplay_improvement->card->japanese_name . '】を手札に戻しました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function faceUpOccupation(Request $request, $game_id, $occupation_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $face_up_occupation = $game->my_player->played_occupations->firstWhere('id', $occupation_id);
        if (is_null($face_up_occupation)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $face_up_occupation->faceUp();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が【'
            . $face_up_occupation->card->japanese_name . '】を表にしました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function faceUpImprovement(Request $request, $game_id, $improvement_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $face_up_improvement = $game->my_player->played_improvements->firstWhere('id', $improvement_id);
        if (is_null($face_up_improvement)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $face_up_improvement->faceUp();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が【'
            . $face_up_improvement->card->japanese_name . '】を表にしました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function faceDownOccupation(Request $request, $game_id, $occupation_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $face_down_occupation = $game->my_player->played_occupations->firstWhere('id', $occupation_id);
        if (is_null($face_down_occupation)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $face_down_occupation->faceDown();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が【'
            . $face_down_occupation->card->japanese_name . '】を表にしました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function faceDownImprovement(Request $request, $game_id, $improvement_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $face_down_improvement = $game->my_player->played_improvements->firstWhere('id', $improvement_id);
        if (is_null($face_down_improvement)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $face_down_improvement->faceDown();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が【'
            . $face_down_improvement->card->japanese_name . '】を表にしました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function discardOccupation(Request $request, $game_id, $occupation_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $game->my_player->load('all_occupations');
        $discard_occupation = $game->my_player->all_occupations->firstWhere('id', $occupation_id);
        if (is_null($discard_occupation)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $discard_occupation->discard();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が職業を捨て札にしました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function discardImprovement(Request $request, $game_id, $improvement_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }
        $game->my_player->load('all_improvements');
        $discard_improvement = $game->my_player->all_improvements->firstWhere('id', $improvement_id);
        if (is_null($discard_improvement)) {
            throw new InvalidActionException('そのカードは存在しません');
        }
        $discard_improvement->discard();

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が小さい進歩を捨て札にしました';
        broadcast(new GameUpdateEvent($game, $message));
    }


    public function drawOccupations(DrawCardsRequest $request, $game_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }

        DB::transaction(function() use ($request, $game) {
            $pile_occupations = $game->getPileOccupations();
            if ($pile_occupations->count() < $request->draw_number) {
                throw new OutOfCardsException();
            }

            for ($i = 0; $i < $request->draw_number; $i++) {
                $draw_occupation_rows[] = [
                    'game_id' => $game->id,
                    'player_id' => $game->my_player->id,
                    'card_id' => $pile_occupations->pop()->id,
                    'status' => CardStatus::IN_HAND,
                ];
            }
            DB::table('game_occupations')->insert($draw_occupation_rows);
        });

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が職業を'
            . $request->draw_number . '枚引きました';
        broadcast(new GameUpdateEvent($game, $message));
    }

    public function drawImprovements(DrawCardsRequest $request, $game_id)
    {
        $game = Game::findOrFail($game_id);
        if (! $game->my_player) {
            throw new InvalidActionException('あなたはプレイヤーではありません');
        }

        DB::transaction(function() use ($request, $game) {
            $pile_improvements = $game->getPileImprovements();
            if ($pile_improvements->count() < $request->draw_number) {
                throw new OutOfCardsException();
            }

            for ($i = 0; $i < $request->draw_number; $i++) {
                $draw_improvement_rows[] = [
                    'game_id' => $game->id,
                    'player_id' => $game->my_player->id,
                    'card_id' => $pile_improvements->pop()->id,
                    'status' => CardStatus::IN_HAND,
                ];
            }
            DB::table('game_improvements')->insert($draw_improvement_rows);
        });

        $message = '[' . $game->my_player->player_order . '] ' .$game->my_player->user->name . 'が小さい進歩を'
            . $request->draw_number . '枚引きました';
        broadcast(new GameUpdateEvent($game, $message));
    }
}
