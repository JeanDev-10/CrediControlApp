<?php
namespace App\Policies;

use App\Models\Pay;
use App\Models\User;

class PayPolicy
{
    public function view(User $user, Pay $pay)
    {
        return $user->id === $pay->debt->user_id;
    }

     /**
     * Determina si el usuario puede crear un pago.
     */
    public function create(User $user, Pay $pay): bool
    {
        return $user->id === $pay->debt->user_id
            && $pay->debt->status === 'pendiente';
    }

       /**
     * Determina si el usuario puede actualizar un pago.
     */
    public function update(User $user, Pay $pay): bool
    {
        return $user->id === $pay->debt->user_id
            && $pay->debt->status === 'pendiente';
    }

     /**
     * Determina si el usuario puede eliminar un pago.
     */
    public function delete(User $user, Pay $pay): bool
    {
        return $user->id === $pay->debt->user_id
            && $pay->debt->status === 'pendiente';
    }

     public function deleteImage(User $user, Pay $pay): bool
    {
        return $user->id === $pay->debt->user_id && $pay->debt->status !== 'pagada';
    }

    public function deleteImages(User $user, Pay $pay): bool
    {
        return $user->id === $pay->debt->user_id && $pay->debt->status !== 'pagada';
    }
}
