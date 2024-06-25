<?php

use App\Http\Controllers\AutheticationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::post('/login', [AutheticationController::class, 'login'])->name('login');
