<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function latestByUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->latest()->first();
    }

    public function filter(array $filters, int $perPage = 10)
    {
        $query = $this->model->where('user_id', auth()->id());

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (! empty($filters['description'])) {
            $query->where('description', 'like', "%{$filters['description']}%");
        }
        if (! empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }
        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
