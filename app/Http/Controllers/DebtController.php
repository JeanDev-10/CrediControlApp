<?php

namespace App\Http\Controllers;

use App\Http\Requests\Debts\StoreDebtRequest;
use App\Http\Requests\Debts\UpdateDebtRequest;
use App\Models\Debt;
use App\Services\ContactService;
use App\Services\DebtService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected DebtService $service,protected ContactService $contactService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['description', 'contact_name', 'date_start', 'status']);
        $debts = $this->service->list($filters, 10);

        return view('debts.index', compact('debts'));
    }

    public function create()
    {
        $this->authorize('create', Debt::class);
        $contacts=$this->contactService->getAll([],10000);
        return view('debts.create',compact('contacts'));
    }

    public function store(StoreDebtRequest $request)
    {
        $this->authorize('create', Debt::class);
        $this->service->create($request->validated());

        return redirect()->route('debts.index')->with('success', 'Deuda creada correctamente.');
    }

    public function show(Debt $debt)
    {
        $this->authorize('view', $debt);

        return view('debts.show', compact('debt'));
    }

    public function edit(Debt $debt)
    {
        $this->authorize('update', $debt);
        $contacts=$this->contactService->getAll([],10000);
        return view('debts.edit', compact('debt','contacts'));
    }

    public function update(UpdateDebtRequest $request, Debt $debt)
    {
        try {
            $this->authorize('update', $debt);
            $this->service->update($debt->id, $request->validated());
            return redirect()->route('debts.index')->with('success', 'Deuda actualizada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Debt $debt)
    {
        $this->authorize('delete', $debt);
        $this->service->delete($debt->id);

        return redirect()->route('debts.index')->with('success', 'Deuda eliminada.');
    }

    public function markAsPaid(Debt $debt)
    {
        $this->authorize('markAsPaid', $debt);
        $this->service->markAsPaid($debt->id);

        return redirect()->route('debts.index')->with('success', 'Deuda marcada como pagada.');
    }
}
