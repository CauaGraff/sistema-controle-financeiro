<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminIS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $tipos = [1, 2]): Response
    {
        // Verifica se o tipo do usuário corresponde ao tipo esperado
        if (Auth::user()->isAdmim()) {
            return $next($request);
        }
        return redirect()->route("login");
    }
}
