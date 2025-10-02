<?php

namespace App\Policies;

use App\Models\Debt;
use App\Models\User;

class DebtPolicy
{
    public function view(User $user, Debt $debt): bool
    {
        return $debt->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true; // cualquier usuario autenticado puede crear en su cuenta
    }

    public function update(User $user, Debt $debt): bool
    {
        // Solo dueño y que la deuda no esté pagada
        return $debt->user_id === $user->id && $debt->status !== 'pagada';
    }

    public function delete(User $user, Debt $debt): bool
    {
        // Solo dueño puede eliminar (si quieres evitar eliminar pagadas, añade condición)
        return $debt->user_id === $user->id;
    }

    public function markAsPaid(User $user, Debt $debt): bool
    {
        return $debt->user_id === $user->id && $debt->status !== 'pagada';
    }
}
