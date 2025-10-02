<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DebtRepositoryInterface extends BaseRepositoryInterface
{
    public function filter(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function getPaysByDebt(array $filters, $id, $perPage = 10);
    public function getAllWithoutPagination(array $filters = []);
}
