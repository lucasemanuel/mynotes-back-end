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
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'Api\\AuthController@login');
    Route::post('check', 'Api\\AuthController@check');
    Route::post('logout', 'Api\\AuthController@logout');
});

// Notes
Route::group([
    'prefix' => '/notes',
    'middleware' => 'apiJwt',
], function ($router) {
    Route::get('/', 'Api\\NoteController@index');
    Route::get('/{id}', 'Api\\NoteController@show');
});

// User
Route::post('/users', 'Api\\UserController@store');
Route::group([
    'prefix' => 'users',
    'middleware' => 'apiJwt',
], function ($router) {
    Route::get('/', 'Api\\UserController@index');
});
