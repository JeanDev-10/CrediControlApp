<?php

namespace App\Http\Controllers;

use App\Services\DebtService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected TransactionService $transactionService, protected DebtService $debtService) {}

    public function index(Request $request)
    {
        $fromTransactions = $request->input('fromTransactions');
        $toTransactions = $request->input('toTransactions');
        $chartData = $this->transactionService->getMonthlyIncomeVsExpenses($fromTransactions,$toTransactions);
        $fromDebts = $request->input('fromDebts');
        $toDebts = $request->input('toDebts');
        $limitDebts = $request->input('limitDebts', 10);
        $data = $this->debtService->getTopContactsWithPendingDebts($limitDebts, $fromDebts, $toDebts);
        return view('dashboard.dashboard', compact('chartData','fromDebts','toDebts','data','fromDebts','toDebts','limitDebts'));
    }
}
