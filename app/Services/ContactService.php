<?php

namespace App\Services;

use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactService
{
    public function __construct(protected ContactRepositoryInterface $repository, protected UserService $userService) {}

    public function getAll(array $filters, int $perPage = 10)
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Consultó listado de contactos');

        return $this->repository->filter($filters, $perPage);
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function getByIdWithDebtsFiltered(array $filters, int $id, $perPage = 10,$contact=null)
    {
        activity()
            ->performedOn($contact)
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Consultó detalle de un contacto');

        return $this->repository->getDebtsByContact($id, $filters, $perPage);
    }

    public function getByIdWithDebtsWithoutFiltered(array $filters, int $id)
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Generó PDF de contactos con deudas');

        return $this->repository->getDebtsByContactWithoutFilters($id, $filters);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function exportAll(array $filters = [])
    {
        activity()
            ->causedBy($this->userService->getUserLoggedIn())
            ->log('Generó PDF de contactos');

        return $this->repository->getAllForExport($filters);
    }
}
