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

        if (! empty($filters['description'])) {
            $query->where('description', 'like', "%{$filters['description']}%");
        }

        if (! empty($filters['contact_name'])) {
            $query->whereHas('contact', function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['contact_name']}%")
                    ->orWhere('lastname', 'like', "%{$filters['contact_name']}%");
            });
        }

        if (! empty($filters['date_start'])) {
            $query->whereDate('date_start', '>=', $filters['date_start']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['debt_id'])) {
            $query->where('id', $filters['debt_id']);
        }

        return $query->latest('created_at')->paginate($perPage)->withQueryString();
    }

    public function getPaysByDebt(array $filters, $id, $perPage = 10)
    {
        $debt = $this->model->find($id);
        // Relación de pagos de esa deuda
        $query = $debt->pays()->newQuery();
        // Total pagado (SIN paginación)
        $totalPaid = (clone $query)->sum('quantity');

        // Filtros sobre los pagos
        if (! empty($filters['quantity'])) {
            $query->where('quantity', 'like', '%'.$filters['quantity'].'%');
        }

        if (! empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }
        $pays = $query->latest()->paginate($perPage)->withQueryString();



        return [
            'pays' => $pays,
            'totalPaid' => $totalPaid,
        ];
    }
    public function getPaysByDebtWithoutPagination(array $filters, $id)
    {
        $debt = $this->model->find($id);
        // Relación de pagos de esa deuda
        $query = $debt->pays()->newQuery();
        // Total pagado (SIN paginación)
        $totalPaid = (clone $query)->sum('quantity');

        // Filtros sobre los pagos
        if (! empty($filters['quantity'])) {
            $query->where('quantity', 'like', '%'.$filters['quantity'].'%');
        }

        if (! empty($filters['date'])) {
            $query->whereDate('date', $filters['date']);
        }
        $pays = $query->latest()->get();

        return [
            'pays' => $pays,
            'totalPaid' => $totalPaid,
        ];
    }
    public function getAllWithoutPagination(array $filters = []){
        $query = $this->model->where('user_id', auth()->id());

        if (! empty($filters['description'])) {
            $query->where('description', 'like', "%{$filters['description']}%");
        }

        if (! empty($filters['contact_name'])) {
            $query->whereHas('contact', function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['contact_name']}%")
                    ->orWhere('lastname', 'like', "%{$filters['contact_name']}%");
            });
        }

        if (! empty($filters['date_start'])) {
            $query->whereDate('date_start', '>=', $filters['date_start']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['debt_id'])) {
            $query->where('id', $filters['debt_id']);
        }

        return $query->latest('created_at')->get();
    }
    public function getTopContactsWithPendingDebts($limit = 10, $from = null, $to = null)
    {
        $query = $this->model
            ->selectRaw('contact_id, COUNT(*) as total_debts, SUM(quantity) as total_amount')
            ->where('status', 'pendiente')
            ->where('user_id', auth()->id())
            ->groupBy('contact_id')
            ->orderByDesc('total_amount');

        if ($from) {
            $query->whereDate('date_start', '>=', $from);
        }
        if ($to) {
            $query->whereDate('date_start', '<=', $to);
        }

        return $query->with('contact')->limit($limit)->get();
    }
}
