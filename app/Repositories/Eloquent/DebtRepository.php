<?php

namespace App\Repositories\Eloquent;

use App\Models\Debt;
use App\Repositories\Interfaces\DebtRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DebtRepository extends BaseRepository implements DebtRepositoryInterface
{
    public function __construct(Debt $model)
    {
        $this->model = $model;
    }

    public function filter(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->where('user_id', auth()->id());

        if (!empty($filters['description'])) {
            $query->where('description', 'like', "%{$filters['description']}%");
        }

        if (!empty($filters['contact_name'])) {
            $query->whereHas('contact', function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['contact_name']}%")
                  ->orWhere('lastname', 'like', "%{$filters['contact_name']}%");
            });
        }

        if (!empty($filters['date_start'])) {
            $query->whereDate('date_start', $filters['date_start']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['debt_id'])) {
            $query->where('id', $filters['debt_id']);
        }


        return $query->latest('created_at')->paginate($perPage)->withQueryString();
    }

    public function getByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }
}
