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

// Googleログイン
Route::get('/auth/google', 'Auth\LoginController@redirectToGoogle')->name('google.login');
Route::get('/auth/google/callback', 'Auth\LoginController@handleGoogleCallback')->name('auth.google_callback');

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'TopController@index')->name('top');

Route::middleware('auth')->group(function () {
    Route::resource('posts', 'PostsController');

    Route::get('/callback', 'GoogleDriveImageController@handleGoogleCallback')->name('google.callback');
    Route::post('/store', 'GoogleDriveImageController@store')->name('drive.store');
    Route::get('/index', 'GoogleDriveImageController@index')->name('drive.index');
});

Route::post('/vision', 'VisionController@analyzeImage')->name('vision.analyzeImage');
Route::get('/vision2', 'VisionController@vision2')->name('vision2');
