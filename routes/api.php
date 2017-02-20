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

Route::middleware('auth:api')->get('/me', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->post('delito/preview', 'ImportarDelitosController@show');
Route::middleware('auth:api')->post('delito/importar', 'ImportarDelitosController@store');
Route::middleware('auth:api')->resource('delito', 'DelitosController', [ 'except' => ['create', 'edit', 'show']]);
Route::middleware('auth:api')->resource('estado', 'EstadosController', [ 'except' => ['create', 'edit', 'show']]);
Route::middleware('auth:api')->resource('turno', 'TurnosController', [ 'except' => ['create', 'edit', 'show']]);
Route::middleware('auth:api')->resource('registro', 'RegistrosController', [ 'except' => ['create', 'edit']]);
Route::middleware('auth:api')->resource('user', 'UsersController', [ 'except' => ['create', 'edit', 'show']]);