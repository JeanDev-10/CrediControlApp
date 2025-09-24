<?php

namespace App\Repositories\Interfaces;
interface BaseRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 10);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
