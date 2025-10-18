<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {}

    /**
     * Obtener todos los usuarios (solo rol client)
     */
    public function getAll(array $filters, int $perPage = 10)
    {
        activity()
            ->causedBy($this->getUserLoggedIn())
            ->log('Consultó el listado de usuarios');
        if (isset($filters['is_active'])) {
            $filters['is_active'] = (int) $filters['is_active'];
        }

        return $this->repository->filter($filters, $perPage);
    }

    /**
     * Obtener un usuario por ID
     */
    public function getById(int $id)
    {
        $user = $this->repository->find($id);
        activity()
            ->performedOn($user)
            ->causedBy($this->getUserLoggedIn())
            ->log("Consultó el detalle del usuario #{$id}");

        return $user;
    }

    /**
     * Crear un nuevo usuario
     */
    public function create(array $data)
    {
        $user = $this->repository->create($data);
        $user->assignRole('client');
        activity()
            ->performedOn($user)
            ->causedBy($this->getUserLoggedIn())
            ->withProperties(['attributes' => $user->toArray()])
            ->log('Creó un nuevo usuario');

        return $user;
    }

    /**
     * Actualizar un usuario existente
     */
    public function update(int $id, array $data)
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user = $this->repository->update($id, $data);

        activity()
            ->performedOn($user)
            ->causedBy($this->getUserLoggedIn())
            ->withProperties(['attributes' => $user->toArray()])
            ->log('Actualizó un usuario');

        return $user;
    }

    /**
     * Eliminar un usuario
     */
    public function delete(int $id)
    {
        $user = $this->repository->find($id);

        activity()
            ->performedOn($user)
            ->causedBy($this->getUserLoggedIn())
            ->log('Eliminó un usuario');

        return $this->repository->delete($id);
    }

    /**
     * Exportar todos los usuarios filtrados (sin paginación)
     */
    public function exportAll(array $filters = [])
    {
        activity()
            ->causedBy($this->getUserLoggedIn())
            ->log('Exportó el listado de usuarios a PDF');
        if (isset($filters['is_active'])) {
            $filters['is_active'] = (int) $filters['is_active'];
        }

        return $this->repository->getAllForExport($filters);
    }

    /**
     * Activar o desactivar un usuario
     */
    public function toggleIsActive(int $id)
    {
        $user = $this->repository->find($id);
        $this->repository->toogleIsActive($id);

        activity()
            ->performedOn($user)
            ->causedBy($this->getUserLoggedIn())
            ->log('Cambiado el estado activo/inactivo del usuario');

        return $user->fresh();
    }

    /**
     * Obtener el usuario logeado (para auditoría)
     */
    public function getUserLoggedIn()
    {
        return auth()->user();
    }
}
