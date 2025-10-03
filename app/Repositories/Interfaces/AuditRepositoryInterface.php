<?php

namespace App\Repositories\Interfaces;

interface AuditRepositoryInterface
{
    public function getAll(array $filters=[],int $perPage=10);
    public function getAllWithoutPaginations(array $filters=[]);
}
