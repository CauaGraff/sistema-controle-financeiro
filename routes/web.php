<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/auth', [LoginController::class, 'authenticate'])->name('login.auth');
    Route::get('/logout', [LoginController::class, 'destroy'])->name('login.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view(view: 'welcome');
    })->name("home");
});