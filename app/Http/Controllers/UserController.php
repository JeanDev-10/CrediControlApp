<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected UserService $service) {}

    /**
     * Mostrar listado de usuarios (solo rol client)
     */
    public function index(Request $request)
    {
        $filters = $request->only(['name', 'lastname', 'email', 'is_active']);
        $users = $this->service->getAll($filters);

        return view('users.index', compact('users'));
    }

    /**
     * Mostrar formulario de creación de usuario
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Guardar un nuevo usuario
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $this->service->create($data);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Mostrar un usuario específico
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $user)
    {
        $this->authorize("update",$user);
        return view('users.edit', compact('user'));
    }

    /**
     * Actualizar usuario
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize("update",$user);
        $data = $request->validated();
        $redirectUrl = $request->input('redirect_to');
        $this->service->update($user->id, $data);
        return redirect($redirectUrl ?? route('users.index'))
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $user, Request $request)
    {
        $this->authorize("delete",$user);
        $redirectUrl = $request->input('redirect_to');
        $this->service->delete($user->id);
        return redirect($redirectUrl ?? route('users.index'))
            ->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Activar o desactivar usuario
     */
    public function toggleIsActive(User $user, Request $request)
    {
        $redirectUrl = $request->input('redirect_to');
        $this->service->toggleIsActive($user->id);
        return redirect($redirectUrl ?? route('users.index'))
            ->with('success', 'Estado de usuario actualizado correctamente..');
    }

    /**
     * Exportar usuarios filtrados a PDF
     */
    public function export(Request $request)
    {
        $filters = $request->only(['name', 'lastname', 'email', 'is_active']);
        $users = $this->service->exportAll($filters);
        $pdf = Pdf::loadView('pdf.users.users', [
            'users' => $users,
            'filters' => $filters,
            'user'=>$this->service->getUserLoggedIn()
        ]);

        return $pdf->stream('usuarios.pdf');
    }
}
