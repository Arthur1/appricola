<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::get('/users/me', 'UserController@getMyData');
Route::get('/pools/list', 'PoolController@list');
Route::get('/games/list', 'GameUtilController@list');
Route::post('/games/', 'GameUtilController@create');

Route::get('/games/{id}/states', 'GamePlayController@states');
Route::post('/games/{id}/pick', 'GamePlayController@pickCards');

Route::put('/games/{game_id}/occupations/{occupation_id}/play', 'GamePlayController@playOccupation');
Route::put('/games/{game_id}/improvements/{improvement_id}/play', 'GamePlayController@playImprovement');
Route::put('/games/{game_id}/occupations/{occupation_id}/unplay', 'GamePlayController@unplayOccupation');
Route::put('/games/{game_id}/improvements/{improvement_id}/unplay', 'GamePlayController@unplayImprovement');
Route::put('/games/{game_id}/occupations/{occupation_id}/discard', 'GamePlayController@discardOccupation');
Route::put('/games/{game_id}/improvements/{improvement_id}/discard', 'GamePlayController@discardImprovement');
Route::put('/games/{game_id}/occupations/{occupation_id}/face_up', 'GamePlayController@faceUpOccupation');
Route::put('/games/{game_id}/improvements/{improvement_id}/face_up', 'GamePlayController@faceUpImprovement');
Route::put('/games/{game_id}/occupations/{occupation_id}/face_down', 'GamePlayController@faceDownOccupation');
Route::put('/games/{game_id}/improvements/{improvement_id}/face_down', 'GamePlayController@faceDownImprovement');
Route::post('/games/{game_id}/occupations/{occupation_id}/pass_left', 'GamePlayController@passOccupationLeft');
Route::post('/games/{game_id}/improvements/{improvement_id}/pass_left', 'GamePlayController@passImprovementLeft');

Route::post('/games/{game_id}/draw_occupations', 'GamePlayController@drawOccupations');
Route::post('/games/{game_id}/draw_improvements', 'GamePlayController@drawImprovements');
