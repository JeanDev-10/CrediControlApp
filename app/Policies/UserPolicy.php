<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede actualizar el modelo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        // Verificar que el usuario está intentando editar un usuario con rol 'client'
        return $model->hasRole('client');
    }

    /**
     * Determina si el usuario puede eliminar el modelo.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        // Verificar que el usuario está intentando eliminar un usuario con rol 'client'
        return $model->hasRole('client');
    }
}
