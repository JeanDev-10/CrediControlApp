<?php
namespace App\Repositories\Interfaces;
interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function latestByUser(int $userId);
    public function filter(array $filters, int $perPage = 10);
    public function getAllWithoutPagination(array $filters = []);
}
