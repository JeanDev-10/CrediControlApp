<?php

namespace App\Repositories\Interfaces;

interface BudgetRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser(int $userId);
}
