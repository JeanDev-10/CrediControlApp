<?php

namespace App\Services;

use App\Repositories\Interfaces\DebtRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Debt;

class DebtService
{

    public function __construct(protected DebtRepositoryInterface $debtRepo)
    {
    }

    public function list(array $filters = [], int $perPage = 10)
    {
        return $this->debtRepo->filter($filters, $perPage);
    }

    public function get(int $id): Debt
    {
        return $this->debtRepo->find($id);
    }

    public function create(array $data): Debt
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::id();
            // default status pendiente unless provided
            $data['status'] = $data['status'] ?? 'pendiente';
            return $this->debtRepo->create($data);
        });
    }

    public function update(int $id, array $data): Debt
    {
        return DB::transaction(function () use ($id, $data) {
            $debt = $this->debtRepo->find($id);
            if ($debt->status === 'pagada') {
                // no se permiten modificaciones si ya está pagada
                throw new \Exception('No se puede modificar una deuda pagada.');
            }
            return $this->debtRepo->update($id, $data);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            return $this->debtRepo->delete($id);
        });
    }

    public function markAsPaid(int $id): Debt
    {
        return DB::transaction(function () use ($id) {
            $debt = $this->debtRepo->find($id);
            if ($debt->status === 'pagada') {
                throw new \Exception('La deuda ya está pagada.');
            }
            $debt->status = 'pagada';
            $debt->save();
            return $debt;
        });
    }
}
