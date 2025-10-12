<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected TransactionService $transactionService) {}

    public function index(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chartData = $this->transactionService->getMonthlyIncomeVsExpenses($from,$to);

        return view('dashboard.dashboard', compact('chartData','from','to'));
    }
}
