<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contacts\StoreContactRequest;
use App\Http\Requests\Contacts\UpdateContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ContactService $service, protected UserService $userService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['name', 'lastname']);
        $contacts = $this->service->getAll($filters);
        activity()
            ->causedBy(auth()->user())
            ->log('Consultó listado de contactos');
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->userService->getUserLoggedIn()->id;
        $this->service->create($data);
        return redirect()->route('contacts.index')->with('success', 'Contacto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact, Request $request)
    {
        $this->authorize('show', $contact);
        $debts = $this->service->getByIdWithDebtsFiltered($request->all(), $contact->id, 10);
         activity()
            ->performedOn($contact)
            ->causedBy(auth()->user())
            ->log('Consultó detalle de un contacto');
        return view('contacts.show', compact('contact', 'debts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = $this->service->getById($id);
        $this->authorize('edit', $contact);

        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $data = $request->validated();
        $this->authorize('update', $contact);
        $this->service->update($contact->id, $data);
        $redirectUrl = $request->input('redirect_to');

        return redirect($redirectUrl ?? route('contacts.index'))
            ->with('success', 'Contacto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);
        $this->service->delete($contact->id);

        return redirect()->route('contacts.index')->with('success', 'Contacto eliminado correctamente.');
    }

    /**
     * export to pdf contacts.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['name', 'lastname']);
        $contacts = $this->service->exportAll($filters);
        $user = $this->userService->getUserLoggedIn();
        $pdf = Pdf::loadView('pdf.contacts.contacts', compact('contacts', 'user', 'filters'));
        activity()
            ->causedBy(auth()->user())
            ->log('Generó PDF de contactos');
        return $pdf->stream('mis-contactos.pdf');
    }

    public function exportContactWithDebtsToPdf(Contact $contact, Request $request)
    {
         $this->authorize('show', $contact);

        $filters = $request->only(['description', 'date_start', 'status']);
        $debts = $this->service->getByIdWithDebtsWithoutFiltered($filters, $contact->id); // sin paginar

        $pdf = Pdf::loadView('pdf.contacts.contacts-with-debts', [
            'user' => $this->userService->getUserLoggedIn(),
            'contact' => $contact,
            'debts' => $debts,
            'filters' => $filters,
        ]);
        activity()
            ->causedBy(auth()->user())
            ->log('Generó PDF de contactos con deudas');
        return $pdf->stream("reporte-contacto-{$contact->id}.pdf");
    }
}
