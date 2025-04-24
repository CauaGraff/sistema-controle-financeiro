<?php

namespace App\Policies;

use App\Models\FornecedorCliente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FornecedorClientePolicy
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
    public function view(User $user, FornecedorCliente $fornecedorCliente): bool
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
    public function update(User $user, FornecedorCliente $fornecedor): bool
    {
        // Admin pode tudo
        if ($user->isAdmim()) {
            return true;
        }

        // Escritório pode editar se a empresa do fornecedor tiver o mesmo id_escritorio
        if ($user->isEscritorio()) {
            return $fornecedor->empresa && $fornecedor->empresa->id_escritorio === $user->id_escritorio;
        }

        // Cliente pode editar se estiver vinculado à empresa do fornecedor
        if ($user->isCliente()) {
            return $user->empresas->contains('id', $fornecedor->id_empresa);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FornecedorCliente $fornecedorCliente): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FornecedorCliente $fornecedorCliente): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FornecedorCliente $fornecedorCliente): bool
    {
        return false;
    }
}
