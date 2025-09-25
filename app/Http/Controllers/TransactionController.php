<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\SetupBudgetRequest;
use App\Http\Requests\Transactions\StoreTransactionRequest;
use App\Http\Requests\Transactions\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use AuthorizesRequests; // Add this line

    public function __construct(protected TransactionService $service) {}

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'description', 'date']);
        $transactions = $this->service->getTransactions($filters, 10);
        $budget = $this->service->getBudget();

        return view('transactions.index', compact('transactions', 'budget'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(StoreTransactionRequest $request)
    {
        $this->service->createTransaction($request->validated());

        return redirect()->route('transactions.index')->with('success', 'Transacción creada');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $this->service->updateTransaction($transaction->id, $request->validated());

        return redirect()->route('transactions.index')->with('success', 'Transacción actualizada');
    }

    public function destroy(Transaction $transaction)
    {

        $this->authorize('delete', $transaction);
        $this->service->deleteTransaction($transaction->id);

        return redirect()->route('transactions.index')->with('success', 'Transacción eliminada');
    }

    public function setupBudget(SetupBudgetRequest $request)
    {
        $this->service->setupBudget($request->validated()->quantity);

        return redirect()->route('transactions.index')->with('success', 'Presupuesto configurado');
    }
}
