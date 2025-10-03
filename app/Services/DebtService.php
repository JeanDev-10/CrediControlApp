<?php

namespace App\Services;

use App\Repositories\Interfaces\DebtRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Debt;

class DebtService
{

    public function __construct(protected DebtRepositoryInterface $debtRepo,protected UserService $userService)
    {
    }

    public function list(array $filters = [], int $perPage = 10)
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Consultó listado de deudas');
        return $this->debtRepo->filter($filters, $perPage);
    }

    public function get(int $id): Debt
    {
        return $this->debtRepo->find($id);
    }
    public function getByIdWithPaysFiltered(array $filters, int $id, int $perPage = 10,$debt=null)
    {
        activity()
            ->performedOn($debt)
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Consultó detalle de una deuda con sus pagos');
        return $this->debtRepo->getPaysByDebt($filters, $id, $perPage);
    }
    public function getByIdWithPaysFilteredWithoutPagination(array $filters, int $id)
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Generó PDF de deudas con pagos');
        return $this->debtRepo->getPaysByDebtWithoutPagination($filters, $id);
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
            $debt_updated=$this->debtRepo->update($id, $data);
            $this->recalculateDebtStatus($debt_updated);
            return $debt_updated;
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
            activity()
            ->performedOn($debt)
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Marcó como pagada una deuda');
            return $debt;

        });
    }
    protected function recalculateDebtStatus($debt): void
    {
        // Reload to get up-to-date relations
        $debt->refresh();

        // Sum all pays' quantities
        // NOTE: Pay model uses 'quantity' column
        $totalPaid = $debt->pays()->sum('quantity');

        if ($totalPaid >= $debt->quantity) {
            if ($debt->status !== 'pagada') {
                $debt->update(['status' => 'pagada']);
            }
        } else {
            if ($debt->status !== 'pendiente') {
                $debt->update(['status' => 'pendiente']);
            }
        }
    }
    public function filterForExport(array $filters = [])
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Generó PDF de deudas');
        return $this->debtRepo->getAllWithoutPagination($filters);
    }
}
