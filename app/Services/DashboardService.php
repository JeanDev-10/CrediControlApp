<?php

namespace App\Services;

use App\Repositories\Interfaces\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(protected DashboardRepositoryInterface $repository, protected DebtService $debtService, protected TransactionService $transactionService, protected UserService $userService) {}

    public function getDashboardStats(int $userId, $limitDebts, $fromDebts, $toDebts, $fromTransactions, $toTransactions): array
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Consultó gráficos de estadísticas');

        return [
            'total_contacts' => $this->repository->getContactCount($userId),
            'pending_debts_count' => $this->repository->getPendingDebtsCount($userId),
            'pending_debts_total' => $this->repository->getPendingDebtsTotal($userId),
            'debts_data' => $this->debtService->getTopContactsWithPendingDebts($limitDebts, $fromDebts, $toDebts),
            'transactions_data' => $this->transactionService->getMonthlyIncomeVsExpenses($fromTransactions, $toTransactions),
        ];
    }
}
