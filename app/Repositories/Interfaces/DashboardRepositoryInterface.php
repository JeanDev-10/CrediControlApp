<?php

namespace App\Repositories\Interfaces;

interface DashboardRepositoryInterface
{
    public function getContactCount(int $userId): int;
    public function getPendingDebtsCount(int $userId): int;
    public function getPendingDebtsTotal(int $userId): float;
}
