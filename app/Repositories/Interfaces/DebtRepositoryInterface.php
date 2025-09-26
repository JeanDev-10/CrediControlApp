<?php

namespace App\Repositories\Interfaces;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DebtRepositoryInterface extends BaseRepositoryInterface
{
    public function filter(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function getByUser(int $userId);
}
