<?php

namespace App\Services;

use App\Repositories\Interfaces\BudgetRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(protected TransactionRepositoryInterface $transactionRepo, protected BudgetRepositoryInterface $budgetRepo, protected UserService $userService) {}

    public function getTransactions(array $filters = [], int $perPage = 10)
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Consultó listado de transacciones');
        return $this->transactionRepo->filter($filters, $perPage);
    }

    public function createTransaction(array $data)
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $budget = $this->budgetRepo->getByUser($userId);

            if (! $budget) {
                throw new \Exception('Debes configurar tu presupuesto primero.');
            }
            // verificar que haya disponibilidad de dinero
            if ($data['type'] == 'egreso' && $data['quantity'] > $budget->quantity) {
                throw new \Exception('No hay suficiente dinero en el presupuesto para realizar esta transacción.');
            }
            $lastTransaction = $this->transactionRepo->latestByUser($userId);
            $previus = $lastTransaction?->after_quantity ?? $budget->quantity;

            // Calcular after_quantity según tipo
            $after = $data['type'] === 'ingreso'
                ? $previus + $data['quantity']
                : $previus - $data['quantity'];

            $transaction = $this->transactionRepo->create([
                'description' => $data['description'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'previus_quantity' => $previus,
                'after_quantity' => $after,
                'user_id' => $userId,
            ]);

            // Actualizar budget
            $this->budgetRepo->update($budget->id, ['quantity' => $after]);
            DB::commit();

            return $transaction;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTransaction(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $transaction = $this->transactionRepo->find($id);
            // Solo última transacción puede editar
            $lastTransaction = $this->transactionRepo->latestByUser(Auth::id());
            if ($transaction->id !== $lastTransaction->id) {
                throw new \Exception('Solo puedes editar tu última transacción.');
            }

            $budget = $this->budgetRepo->getByUser(Auth::id());
            if (! $budget) {
                throw new \Exception('Debes configurar tu presupuesto primero.');
            }
            // verificar que haya disponibilidad de dinero
            if ($data['type'] == 'egreso' && $data['quantity'] > ($budget->quantity+$transaction->quantity)) {
                throw new \Exception('No hay suficiente dinero en el presupuesto para realizar esta transacción.');
            }
            $previus = $transaction->previus_quantity;
            $after = $data['type'] === 'ingreso'
                ? $previus + $data['quantity']
                : $previus - $data['quantity'];

            $updatedTransaction = $this->transactionRepo->update($id, [
                'description' => $data['description'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'after_quantity' => $after,
            ]);
            $this->budgetRepo->update($budget->id, ['quantity' => $after]);
            DB::commit();

            return $updatedTransaction;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteTransaction(int $id)
    {
        DB::beginTransaction();

        try {
            $transaction = $this->transactionRepo->find($id);
            $lastTransaction = $this->transactionRepo->latestByUser(Auth::id());

            if ($transaction->id !== $lastTransaction->id) {
                throw new \Exception('Solo puedes eliminar tu última transacción.');
            }

            $budget = $this->budgetRepo->getByUser(Auth::id());
            $this->budgetRepo->update($budget->id, ['quantity' => $transaction->previus_quantity]);
            $deleted = $this->transactionRepo->delete($id);
            DB::commit();

            return $deleted;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function setupBudget(float $quantity)
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $budget = $this->budgetRepo->getByUser($userId);
            $previusQuantity = 0;
            if (! $budget) {
                $budget = $this->budgetRepo->create(['user_id' => $userId, 'quantity' => $quantity]);
            } else {
                $previusQuantity = $budget->quantity;
                $this->budgetRepo->update($budget->id, ['quantity' => $quantity]);
            }

            // Registrar como transacción
            $transaction = $this->transactionRepo->create([
                'description' => 'Actualización de presupuesto',
                'type' => 'actualizacion',
                'quantity' => $quantity,
                'previus_quantity' => $previusQuantity,
                'after_quantity' => $quantity,
                'user_id' => $userId,
            ]);
            activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Modificó su presupuesto');
            DB::commit();

            return $transaction;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getBudget()
    {
        return $this->budgetRepo->getByUser(Auth::id());
    }

    public function getAllWithoutPagination(array $filters = [])
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Exportó listado de transacciones');
        return $this->transactionRepo->getAllWithoutPagination($filters);
    }
}
