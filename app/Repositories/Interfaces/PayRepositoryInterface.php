<?php
namespace App\Repositories\Interfaces;
interface PayRepositoryInterface extends BaseRepositoryInterface
{
    public function filter(array $filters, int $perPage = 10);
}
