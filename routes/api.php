<?php

use Illuminate\Http\Request;

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

Route::group(['middlware' => 'auth:api'], function () {
    // TURN Endpoints
    Route::post('/turns')->uses('TurnsController@store')->name('turns.store');
    Route::patch('/turns/{turn}')->uses('TurnsController@update')->name('turns.update');
    Route::delete('/turns/{turn}')->uses('TurnsController@destroy')->name('turns.destroy');
    // MOVIE Endpoints
    Route::post('/movies')->uses('MoviesController@store')->name('movies.store');
});

Route::get('/turns')->uses('TurnsController@index')->name('turns.index');
Route::get('/movies')->uses('MoviesController@index')->name('movies.index');
