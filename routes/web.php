<?php

use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login-test', function () {
    return view('google_login');
});


Route::get('auth/{driver}/redirect', [SocialController::class, 'redirect'])->name('social.login');
Route::get('auth/{driver}/callback', [SocialController::class, 'callback']);


