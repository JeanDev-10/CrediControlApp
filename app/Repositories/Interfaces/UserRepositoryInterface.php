<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function filter(array $filters, int $perPage = 10);
    public function getAllForExport(array $filters = []);
    public function toogleIsActive($id);
}
