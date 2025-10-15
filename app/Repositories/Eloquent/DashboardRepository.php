<?php

namespace App\Repositories\Eloquent;

use App\Models\Contact;
use App\Models\Debt;
use App\Repositories\Interfaces\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getContactCount(int $userId): int
    {
        return Contact::where('user_id', $userId)->count();
    }

    public function getPendingDebtsCount(int $userId): int
    {
        return Debt::where('user_id', $userId)
            ->where('status', 'pendiente')
            ->count();
    }

    public function getPendingDebtsTotal(int $userId): float
    {
        // Obtener las deudas pendientes
        $pendingDebts = Debt::where('user_id', $userId)
            ->where('status', 'pendiente')
            ->get();

        $totalDebt = 0.0;

        // Sumar todas las deudas pendientes
        foreach ($pendingDebts as $debt) {
            $totalDebt += $debt->quantity;
        }

        // Obtener los pagos realizados para las deudas pendientes
        $totalPayments = Debt::where('user_id', $userId)
            ->where('status', 'pendiente')
            ->with('pays') // Cargar los pagos de cada deuda
            ->get()
            ->sum(function ($debt) {
                // Sumar los pagos realizados en cada deuda
                return $debt->pays->sum('quantity');
            });

        // Calcular el total de la deuda pendiente (deuda - pagos)
        $totalDebt -= $totalPayments;

        return $totalDebt;
    }
}
