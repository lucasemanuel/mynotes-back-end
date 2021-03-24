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

// Auth
Route::group([
    'prefix' => '/auth',
], function ($router) {
    Route::post('/login', 'Api\\AuthController@login');
    Route::post('/logout', 'Api\\AuthController@logout');
    Route::post('/refresh', 'Api\\AuthController@refresh');
});

// Notes
Route::group([
    'prefix' => '/notes',
    'middleware' => 'auth',
], function ($router) {
    Route::get('/', 'Api\\NoteController@index');
    Route::get('/{note}', 'Api\\NoteController@show');
    Route::post('/', 'Api\\NoteController@store');
    Route::put('/{note}', 'Api\\NoteController@update');
    Route::patch('/{note}', 'Api\\NoteController@mark');
    Route::delete('/{note}', 'Api\\NoteController@destroy');
});

// User
Route::group([
    'prefix' => 'users',
], function ($router) {
    Route::get('/', 'Api\\UserController@index');
    Route::post('/', 'Api\\UserController@store');
});
