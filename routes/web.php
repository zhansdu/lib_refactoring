<?php

use Illuminate\Support\Facades\Route;

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

Route::post('login', 'Auth\LoginController')->name('web-login');
Route::post('logout', 'Auth\LogoutController')->name('web-logout');
Route::get('user', 'Auth\UserController')->name('web-user');

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'web-admin']], static function () {
    Route::get('{any?}', static function () {
        return view('admin');
    })->where('any', '.*')->name('admin');
});

Route::group(['prefix' => ''], static function () {
    Route::get('{any}', static function () {
        return view('user');
    })->where('any', '.*')->middleware('web')->name('user');
});
