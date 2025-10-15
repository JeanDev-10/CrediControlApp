<?php

namespace App\Http\Controllers;

use App\Http\Requests\Debts\StoreDebtRequest;
use App\Http\Requests\Debts\UpdateDebtRequest;
use App\Models\Debt;
use App\Services\ContactService;
use App\Services\DebtService;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected DebtService $service, protected ContactService $contactService, protected UserService $userService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['description', 'contact_name', 'date_start', 'status']);
        $debts = $this->service->list($filters, 10);

        return view('debts.index', compact('debts'));
    }

    public function create(Request $request)
    {
        $contact_id = $request->query('contact_id');
        $contacts = $this->contactService->getAll(['contact_id' => $contact_id], 10000);

        return view('debts.create', compact('contacts'));
    }

    public function store(StoreDebtRequest $request)
    {
        $this->authorize('create', Debt::class);
        $this->service->create($request->validated());
        $redirectUrl = $request->input('redirect_to');

        return redirect($redirectUrl ?? route('debts.index'))
            ->with('success', 'Deuda creada correctamente');
    }

    public function show(Debt $debt, Request $request)
    {
        $this->authorize('view', $debt);
        $result = $this->service->getByIdWithPaysFiltered($request->all(), $debt->id, 10,$debt);

        $pays = $result['pays'];
        $totalPaid = $result['totalPaid'];
        $remaining = max(0, $debt->quantity - $totalPaid); // nunca negativo

        return view('debts.show', compact('debt', 'pays', 'totalPaid', 'remaining'));
    }

    public function edit(Debt $debt)
    {
        $this->authorize('update', $debt);
        $contacts = $this->contactService->getAll([], 10000);

        return view('debts.edit', compact('debt', 'contacts'));
    }

    public function update(UpdateDebtRequest $request, Debt $debt)
    {
        try {
            $this->authorize('update', $debt);
            $this->service->update($debt->id, $request->validated());

            $redirectUrl = $request->input('redirect_to');

            return redirect($redirectUrl ?? route('debts.index'))
                ->with('success', 'Deuda actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Debt $debt, Request $request)
    {
        $this->authorize('delete', $debt);
        $this->service->delete($debt->id);
        $redirectUrl = $request->input('redirect_to');

        return redirect($redirectUrl ?? route('debts.index'))
            ->with('success', 'Deuda eliminada correctamente');
    }

    public function markAsPaid(Debt $debt)
    {
        $this->authorize('markAsPaid', $debt);
        $this->service->markAsPaid($debt->id);

        return redirect()->route('debts.index')->with('success', 'Deuda marcada como pagada.');
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['description', 'contact_name', 'date_start', 'status']);
        $debts = $this->service->filterForExport($filters);
        $user = $this->userService->getUserLoggedIn();
        $pdf = Pdf::loadView('pdf.debts.index', compact('debts', 'user', 'filters'));

        return $pdf->stream('deudas.pdf'); // 👈 se abre en navegador
    }

    public function exportDebtWithPaysToPdf(Debt $debt, Request $request)
    {
        $filters = $request->only(['quantity', 'date']);
        $result = $this->service->getByIdWithPaysFilteredWithoutPagination($filters, $debt->id);
        $pays = $result['pays'];
        $totalPaid = $result['totalPaid'];
        $remaining = $debt->quantity - $totalPaid; // nunca negativo
        $user = $this->userService->getUserLoggedIn();
        $pdf = Pdf::loadView('pdf.debts.debts-with-pays', compact('pays', 'debt', 'user', 'filters','totalPaid','remaining'));

        return $pdf->stream("reporte-deuda-{$debt->id}.pdf"); // 👈 se abre en navegador
    }

}
