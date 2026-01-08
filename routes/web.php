<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login-test', function () {
    return view('google_login');
});
Route::get('auth/{driver}/redirect', [GoogleController::class, 'redirect']);
Route::get('auth/{driver}/callback', [GoogleController::class, 'callback']);
