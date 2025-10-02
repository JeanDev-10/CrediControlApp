<?php

namespace App\Repositories\Eloquent;

use App\Models\Pay;
use App\Repositories\Interfaces\PayRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayRepository extends BaseRepository implements PayRepositoryInterface
{
    public function __construct(Pay $model)
    {
        $this->model = $model;
    }

    public function filter(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->whereHas('debt', function ($q) {
            $q->where('user_id', auth()->id());
        });

        $query->when($filters['contact_name'] ?? null, function ($q, $name) {
            $q->whereHas('debt.contact', function ($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
        })
            ->when($filters['quantity'] ?? null, fn ($q, $qty) => $q->where('quantity', $qty))
            ->when($filters['date'] ?? null, fn ($q, $date) => $q->whereDate('date', $date));

        return $query->latest()->paginate($perPage)->withQueryString();
    }
    public function getAllWithoutPagination(array $filters = [])
    {
        $query = $this->model->whereHas('debt', function ($q) {
            $q->where('user_id', auth()->id());
        });

        $query->when($filters['contact_name'] ?? null, function ($q, $name) {
            $q->whereHas('debt.contact', function ($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
        })
            ->when($filters['quantity'] ?? null, fn ($q, $qty) => $q->where('quantity', $qty))
            ->when($filters['date'] ?? null, fn ($q, $date) => $q->whereDate('date', $date));

        return $query->latest()->get();
    }
}
