<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

// Route::resource('delito', 'DelitosController');
// Route::resource('estado', 'EstadosController');
// Route::resource('registro', 'RegistrosController');

Auth::routes();

Route::get('/home', 'HomeController@index');
