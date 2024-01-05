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

// 年齢確認
Route::match(['get', 'post'], '/verify-age', 'VerifyAge')->name('verify_age');

//LINEログイン
Route::get('/line_auth', 'LineLoginController@lineLogin')->name('line.login');
Route::get('/top/line_auth', 'LineLoginController@handleLineCallback')->name('auth.line_callback');

// Googleログイン
Route::get('/auth/google', 'Auth\LoginController@redirectToGitHub')->name('google.login');
Route::get('/auth/google/callback', 'Auth\LoginController@handleGitHubCallback')->name('auth.google_callback');

// GitHubログイン
Route::get('/auth/github', 'Auth\LoginController@redirectToGitHub')->name('github.login');
Route::get('/auth/github/callback', 'Auth\LoginController@handleGitHubCallback')->name('auth.github_callback');

// Liffログイン
Route::get('/lifflogin', 'LiffLoginController@login')->name('liff.login');

Route::get('/', 'TopController@index')->name('top');

Route::middleware('auth')->group(function () {
    Route::get('/news', 'NewsController@index');

    Route::resource('posts', 'PostsController');

    Route::get('/callback', 'GoogleDriveImageController@handleGoogleCallback')->name('google.callback');
    Route::post('/store', 'GoogleDriveImageController@store')->name('drive.store');
    Route::get('/index', 'GoogleDriveImageController@index')->name('drive.index');
    Route::delete('/destroy/{imageId}', 'GoogleDriveImageController@destroy')->name('drive.destroy');

    Route::post('/vision', 'VisionController@analyzeImage')->name('vision.analyzeImage');
    Route::get('/vision2', 'VisionController@vision2')->name('vision2');

    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/github/index', 'GitHubController@index')->name('github.index');

    Route::get('/user/{username}', 'MyPageController@index')->name('user');
});
