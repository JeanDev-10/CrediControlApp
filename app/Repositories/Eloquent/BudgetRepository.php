<?php

namespace App\Repositories\Eloquent;

use App\Models\Budget;
use App\Repositories\Interfaces\BudgetRepositoryInterface;

class BudgetRepository extends BaseRepository implements BudgetRepositoryInterface
{
    public function __construct(Budget $model)
    {
        $this->model = $model;
    }

    public function getByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }
}
