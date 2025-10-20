<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AuditRepositoryInterface;
use Spatie\Activitylog\Models\Activity;

class AuditRepository implements AuditRepositoryInterface
{
    protected $model;

    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = $this->model::query();

        // Filtro por descripción
        if (! empty($filters['description'])) {
            $query->where('description', 'like', '%'.$filters['description'].'%');
        }

        // Filtro por fecha
        if (! empty($filters['from']) && ! empty($filters['to'])) {
            $query->whereBetween('created_at', [$filters['from'], $filters['to']]);
        }
        if (! empty($filters['user_id'])) {
            $query->where('causer_id', $filters['user_id']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getAllWithoutPaginations(array $filters = [])
    {
        $query = $this->model::query();

        // Filtro por descripción
        if (! empty($filters['description'])) {
            $query->where('description', 'like', '%'.$filters['description'].'%');
        }

        // Filtro por fecha
        if (! empty($filters['from']) && ! empty($filters['to'])) {
            $query->whereBetween('created_at', [$filters['from'], $filters['to']]);
        }
        if (! empty($filters['user_id'])) {
            $query->where('causer_id', $filters['user_id']);
        }

        return $query->latest()->get();
    }
}
