<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(private TransactionRepositoryInterface $transactionRepository)
    {
        //
    }

    /**
     * Determina si el usuario puede ver la transacción.
     */
    public function show(User $user, Transaction $transaction)
    {
        // Solo el dueño
        if ($transaction->user_id !== $user->id) {
            return false;
        }

        // Debe ser la última transacción
        $latest = $this->transactionRepository->latestByUser($user->id);

        return $latest && $latest->id === $transaction->id;
    }
    /**
     * Determina si el usuario puede actualizar la transacción.
     */
    public function update(User $user, Transaction $transaction)
    {
        // Solo el dueño
        if ($transaction->user_id !== $user->id) {
            return false;
        }

        // Debe ser la última transacción
        $latest = $this->transactionRepository->latestByUser($user->id);

        return $latest && $latest->id === $transaction->id;
    }

    /**
     * Determina si el usuario puede eliminar la transacción.
     */
    public function delete(User $user, Transaction $transaction)
    {
        // Solo el dueño
        if ($transaction->user_id !== $user->id) {
            return false;
        }

        // Debe ser la última transacción
        $latest = $this->transactionRepository->latestByUser($user->id);

        return $latest && $latest->id === $transaction->id;
    }
}
