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
Route::get('/games/list', 'GameUtilController@list');
Route::post('/games/', 'GameUtilController@create');
Route::get('/games/{id}/states', 'GamePlayController@states');
Route::get('/pools/list', 'PoolController@list');
