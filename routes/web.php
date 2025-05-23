<?php

use App\Models\User;
use App\Http\Middleware\AdminIS;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\ContaBancoController;
use App\Http\Controllers\ExportacaoController;
use App\Http\Controllers\FavorecidoController;
use App\Http\Controllers\LancamentoController;
use App\Http\Controllers\CategoriaContasController;
use App\Http\Controllers\FornecedorClienteController;
use App\Http\Controllers\LancamentoRecorrenciaController;

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
        Route::get('/', [AdmController::class, 'index'])->name("home.adm");

        /**USUARIOS */
        Route::prefix('/usuarios')->group(function () {
            Route::get('/{type}', [UsersController::class, 'index'])->name("adm.usuarios");
            Route::get('/{type}/cadastrar', [UsersController::class, 'formUser'])->name("adm.cadastro.usuarios");
            Route::post('/cadastrar/register', [UsersController::class, 'save'])->name('adm.cadastro.usuarios.post');
            Route::get('/{id}/edit', [UsersController::class, 'edit'])->name("adm.usuarios.edit");
            Route::put('/{id}/update', [UsersController::class, 'update'])->name("adm.usuarios.update");
            Route::get('/{id}/delete', [UsersController::class, "delete"])->name('adm.usuarios.delete');

        });
        Route::get('/buscar-usuario', [UsersController::class, 'buscarUsuario'])->name('buscar.usuario');

        Route::prefix('/empresas')->group(function () {
            Route::get('/', [EmpresasController::class, 'index'])->name("adm.empresas");
            Route::get('/cadastrar', [EmpresasController::class, 'create'])->name("adm.cadastro.empresas");
            Route::post('/cadastrar/register', [EmpresasController::class, 'save'])->name('adm.cadastro.empresas.post');
            Route::get('/{id}/edit', [EmpresasController::class, 'edit'])->name("adm.empresas.edit");
            Route::put('/{id}/update', [EmpresasController::class, 'update'])->name("adm.empresas.update");
            Route::get('/{id}/delete', [EmpresasController::class, "delete"])->name('adm.empresas.delete');
            Route::get('/{id}/show', [EmpresasController::class, 'show'])->name('adm.empresas.show');
            Route::post('/add-usuario/{id}', [EmpresasController::class, 'addUsuario'])->name('adm.empresas.addUsuario');
            Route::get('{idEmpresa}/remove-usuario/{idUser}', [EmpresasController::class, 'removeUsuario'])->name('adm.empresas.removeUsuario');
        });


        Route::post('/exportar-contabilidade/{idEmpresa}', [ExportacaoController::class, 'exportarLancamentos'])->name('exportar.contabilidade');


    });
});

Route::middleware(['auth'])->group(function () {

    Route::get('/', [CalendarioController::class, "index"])->name("home");
    Route::post('/calendario/eventos', [CalendarioController::class, 'postEventos'])->name('calendario.eventos.post');
    Route::post('/calendario/lacamentos', [CalendarioController::class, 'postLancamentos'])->name('calendario.lancamentos.post');

    Route::prefix('/empresas')->group(function () {
        Route::get('/selecionar/{id}', [EmpresasController::class, 'definirEmpresa'])->name('empresa.definir');
    });

    /**LANCAMENTOS CAIXA */
    Route::prefix('lancamentos')->group(function () {
        Route::get('pagamentos', [LancamentoController::class, 'indexPagamentos'])->name('lancamentos.pagamentos.index');
        Route::get('pagamentos/create', [LancamentoController::class, 'create'])->name('lancamentos.pagamentos.create');
        Route::post('pagamentos/store', [LancamentoController::class, 'store'])->name('lancamentos.pagamentos.store');
        Route::delete('pagamentos/{lancamento}/delet', [LancamentoController::class, 'destroy'])->name('lancamentos.pagamentos.destroy');
        Route::get('pagamentos/{lancamento}/pagar', [LancamentoController::class, 'formBaixa'])->name('lancamentos.pagamentos.baixa');
        Route::post('pagamentos/{lancamento}/baixa', [LancamentoController::class, 'baixaStore'])->name('lancamentos.pagamentos.baixa.store');

        // Rotas para recebimentos
        Route::get('recebimentos', [LancamentoController::class, 'indexRecebimentos'])->name('lancamentos.recebimentos.index');
        Route::get('recebimentos/create', [LancamentoController::class, 'create'])->name('lancamentos.recebimentos.create');
        Route::post('recebimentos', [LancamentoController::class, 'store'])->name('lancamentos.recebimentos.store');
        Route::delete('recebimentos/{lancamento}', [LancamentoController::class, 'destroy'])->name('lancamentos.recebimentos.destroy');
        Route::get('recebimentos/{lancamento}/pagar', [LancamentoController::class, 'formbaixa'])->name('lancamentos.recebimentos.baixa');
        Route::post('recebimentos/{lancamento}/baixa', [LancamentoController::class, 'baixaStore'])->name('lancamentos.recebimentos.baixa.store');
        Route::get('lancamentos', [LancamentoController::class, 'index'])->name('lancamentos.index');


        Route::get('/{lancamento}/edit', [LancamentoController::class, 'edit'])->name('lancamentos.edit');
        Route::put('/{lancamento}/update', [LancamentoController::class, 'update'])->name('lancamentos.update');
        Route::get('/{lancamento}/baixa', [LancamentoController::class, 'deleteBaixa'])->name('lancamentos.baixa.delete');

        Route::post('export', [LancamentoController::class, 'export'])->name('lancamentos.export');

        Route::get('/{lancamento}/anexo-delet', [LancamentoController::class, 'deleteFileFromStorage'])->name('lancamentos.anexo.delete');
    });
    Route::resource('categorias', CategoriaContasController::class);

    Route::resource('favorecidos', FornecedorClienteController::class);

    Route::resource('contas_banco', ContaBancoController::class);


    Route::prefix('recorrencias')->name('recorrencias.')->group(function () {
        Route::get('/', [LancamentoRecorrenciaController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [LancamentoRecorrenciaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [LancamentoRecorrenciaController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [LancamentoRecorrenciaController::class, 'destroy'])->name('destroy');
    });
});
