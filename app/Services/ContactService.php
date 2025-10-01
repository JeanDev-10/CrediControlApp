<?php

namespace App\Services;

use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactService
{

    public function __construct(protected ContactRepositoryInterface $repository)
    {
    }

    public function getAll(array $filters, int $perPage = 10)
    {
        return $this->repository->filter($filters, $perPage);
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }
    public function getByIdWithDebtsFiltered(array $filters,int $id, $perPage = 10)
    {
        return $this->repository->getDebtsByContact($id,$filters,$perPage);
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
}
