<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Empresas;
use App\Policies\EmpresasPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * As policies mapeadas para os modelos.
     */
    protected $policies = [
        Empresas::class => EmpresasPolicy::class,
    ];

    /**
     * Registre os serviços de autenticação/autorização.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
