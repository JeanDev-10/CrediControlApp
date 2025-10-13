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
        return Debt::where('user_id', $userId)
            ->where('status', 'pendiente')
            ->sum('quantity');
    }
}
