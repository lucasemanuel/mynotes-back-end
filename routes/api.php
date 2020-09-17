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

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'Api\\AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group([
    'prefix' => '/users',
    'middleware' => 'apiJwt',
], function ($router) {
    Route::get('', 'Api\\UserController@index');
    Route::get('/{id}', 'Api\\UserController@show');
    Route::post('', 'Api\\UserController@store');
    Route::post('{id}', 'Api\\UserController@update');
    Route::delete('{id}', 'Api\\UserController@destroy');
});

Route::get('/notes', 'Api\\NoteController@index');
Route::get('/note/{id}', 'Api\\NoteController@show');

