<?php

namespace App\Http\Controllers;

use App\Services\DebtService;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected TransactionService $transactionService, protected DebtService $debtService, protected UserService $userService) {}

    public function index(Request $request)
    {
        $fromTransactions = $request->input('fromTransactions');
        $toTransactions = $request->input('toTransactions');
        $chartData = $this->transactionService->getMonthlyIncomeVsExpenses($fromTransactions,$toTransactions);
        $fromDebts = $request->input('fromDebts');
        $toDebts = $request->input('toDebts');
        $limitDebts = $request->input('limitDebts', 10);
        $data = $this->debtService->getTopContactsWithPendingDebts($limitDebts, $fromDebts, $toDebts);
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Consultó gráficos de estadísticas');
        return view('dashboard.dashboard', compact('chartData','fromDebts','toDebts','data','fromDebts','toDebts','limitDebts'));
    }
}
