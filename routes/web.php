<?php

use App\Http\Controllers\AutheticationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::post('/login', [AutheticationController::class, 'login'])
    ->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::post('/logout', [AutheticationController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');
