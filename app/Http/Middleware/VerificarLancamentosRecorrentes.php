<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class VerificarLancamentosRecorrentes
{
    public function handle($request, Closure $next)
    {
        if (!Cache::has('lancamentos_processados')) {
            Artisan::call('lancamentos:processar-recorrentes');
            Cache::put('lancamentos_processados', true, now()->addMinutes(0)); // Evita execução repetitiva
        }

        return $next($request);
    }
}
