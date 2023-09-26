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

//LINEログイン
Route::get('/line_auth', 'LineLoginController@lineLogin')->name('line.login');
Route::get('/top/line_auth', 'LineLoginController@handleLineCallback')->name('auth.line_callback');

Route::get('/', 'TopController@index')->name('top');

Route::resource('posts', 'PostsController');

