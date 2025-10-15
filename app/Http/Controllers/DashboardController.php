<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\DebtService;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService, protected UserService $userService) {}

    public function index(Request $request)
    {
        $fromTransactions = $request->input('fromTransactions');
        $toTransactions = $request->input('toTransactions');
        $fromDebts = $request->input('fromDebts');
        $toDebts = $request->input('toDebts');
        $limitDebts = $request->input('limitDebts', 10);
        $stats = $this->dashboardService->getDashboardStats($this->userService->getUserLoggedIn()->id,$limitDebts,$fromDebts,$toDebts,$fromTransactions,$toTransactions);
        return view('dashboard.dashboard', compact('stats','fromDebts','toDebts','fromDebts','toDebts','limitDebts'));
    }
}
