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
            return view(view: 'admin.home');
        })->name("home.adm");
        /**USUARIOS */
        Route::prefix('/usuarios')->group(function () {
            Route::get('/{type}', [UsersController::class, 'index'])->name("adm.usuarios");
            Route::get('/{type}/cadastrar', [UsersController::class, 'formUser'])->name("adm.cadastro.usuarios");
            Route::post('/cadastrar/register', [UsersController::class, 'save'])->name('adm.cadastro.usuarios.post');
            Route::get('/{id}/edit', [UsersController::class, 'edit'])->name("adm.usuarios.edit");
            Route::put('/{id}/update', [UsersController::class, 'update'])->name("adm.usuarios.update");
            Route::get('/{id}/delete', [UsersController::class, "delete"])->name('adm.usuarios.delete');
        });

        // Route::prefix('/empresas')->group(function () {
        //     Route::get('/', [EmpresasController::class, 'index'])->name("adm.empresas");
        // });
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view(view: 'welcome');
    })->name("home");
});
