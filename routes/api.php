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
Route::post('/auth/login', 'Api\\AuthController@login');
Route::group([
    'prefix' => '/auth',
    'middleware' => 'auth:api',
], function ($router) {
    Route::post('/logout', 'Api\\AuthController@logout');
    Route::post('/refresh', 'Api\\AuthController@refresh');
});

// Notes
Route::group([
    'prefix' => '/notes',
    'middleware' => 'auth:api',
], function ($router) {
    Route::get('/', 'Api\\NoteController@index');
    Route::get('/{id}', 'Api\\NoteController@show');
});

// User
Route::post('/users', 'Api\\UserController@store');
Route::group([
    'prefix' => 'users',
    'middleware' => 'auth:api',
], function ($router) {
    Route::get('/', 'Api\\UserController@index');
});
