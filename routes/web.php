<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\AdminIS;
/**ROTAS */

/**LOGIN */
Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/auth', [LoginController::class, 'authenticate'])->name('login.auth');
    Route::get('/logout', [LoginController::class, 'destroy'])->name('login.destroy');
});
/**ADM */
Route::middleware(['auth', AdminIS::class])->group(function () {
    Route::prefix('adm')->group(function () {
        Route::get('/', function () {
            return view(view: 'admin.pages.home');
        })->name("home.adm");
        /**USUARIOS */
        Route::prefix('/usuarios')->group(function () {
            Route::get('/{type}', [UsersController::class, 'index'])->name("usuarios.adm");

        });

    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view(view: 'welcome');
    })->name("home");
});
