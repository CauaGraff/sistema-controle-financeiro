<?php

namespace App\Policies;

use App\Models\CategoriaContas;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoriaContasPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CategoriaContas $categoriaContas): bool
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
    public function update(User $user, CategoriaContas $categoria): bool
    {
        // Admin pode tudo
        if ($user->isAdmim()) {
            return true;
        }

        // Escritório pode editar se a empresa da categoria for do mesmo escritório
        if ($user->isEscritorio()) {
            return $categoria->empresa && $categoria->empresa->id_escritorio === $user->id_escritorio;
        }

        // Cliente pode editar se estiver vinculado à empresa da categoria
        if ($user->isCliente()) {
            return $user->empresas->contains($categoria->empresa);
        }

        // Se não se encaixar, nega acesso
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CategoriaContas $categoriaContas): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CategoriaContas $categoriaContas): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CategoriaContas $categoriaContas): bool
    {
        return false;
    }
}
