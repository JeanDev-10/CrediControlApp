<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(private ContactRepositoryInterface $transactionRepository)
    {
        //
    }

    /**
     * Determina si el usuario puede ver la transacción.
     */
    public function show(User $user, Contact $contact)
    {
        // Solo el dueño
        if ($contact->user_id !== $user->id) {
            return false;
        }
        return true;
    }
    /**
     * Determina si el usuario puede ver la vista de edit transacción.
     */
    public function edit(User $user, Contact $contact)
    {
        // Solo el dueño
        if ($contact->user_id !== $user->id) {
            return false;
        }
        return true;
    }

    /**
     * Determina si el usuario puede actualizar la transacción.
     */
    public function update(User $user, Contact $contact)
    {
        // Solo el dueño
        if ($contact->user_id !== $user->id) {
            return false;
        }
        return true;
    }

    /**
     * Determina si el usuario puede eliminar la transacción.
     */
    public function delete(User $user, Contact $contact)
    {
        // Solo el dueño
        if ($contact->user_id !== $user->id) {
            return false;
        }
        return true;
    }
}
