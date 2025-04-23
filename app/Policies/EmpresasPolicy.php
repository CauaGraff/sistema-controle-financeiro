<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Empresas;

class EmpresasPolicy
{
    public function view(User $user, Empresas $empresa): bool
    {
        return $user->id_escritorio === $empresa->id_escritorio;
    }

    public function update(User $user, Empresas $empresa): bool
    {
        return $user->id_escritorio === $empresa->id_escritorio;
    }

    public function delete(User $user, Empresas $empresa): bool
    {
        return $user->id_escritorio === $empresa->id_escritorio;
    }
}