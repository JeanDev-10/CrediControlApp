<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contacts\StoreContactRequest;
use App\Http\Requests\Contacts\UpdateContactRequest;
use App\Models\Contact;
use App\Models\Transaction;
use App\Services\ContactService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected ContactService $service) {}
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $filters = $request->only(['name', 'lastname']);
        $contacts = $this->service->getAll($filters);

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
        $data['user_id'] = auth()->id();
        $this->service->create($data);
        return redirect()->route('contacts.index')->with('success', 'Contacto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $contact = $this->service->getById($id);
        $this->authorize('show', $contact);
        return view('contacts.show', compact('contact'));
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
        return redirect()->route('contacts.index')->with('success', 'Contacto actualizado correctamente.');
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
}
