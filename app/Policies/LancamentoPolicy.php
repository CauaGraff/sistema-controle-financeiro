<?php

namespace App\Policies;

use App\Models\Lancamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LancamentoPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lancamento $lancamento): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lancamento $lancamento): bool
    {
        // Admin pode tudo
        if ($user->isAdmim()) {
            return true;
        }

        // Escritório pode editar se a empresa do lançamento for do mesmo escritório
        if ($user->isEscritorio()) {
            return $lancamento->empresa && $lancamento->empresa->id_escritorio === $user->id_escritorio;
        }

        // Cliente pode editar se estiver vinculado à empresa o lançamento
        if ($user->isCliente()) {
            return $user->empresas->contains($lancamento->empresa);
        }

        // Se não se encaixar, nega acesso
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lancamento $lancamento): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lancamento $lancamento): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lancamento $lancamento): bool
    {
        return false;
    }
}
