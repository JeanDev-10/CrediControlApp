<?php

namespace App\Services;

use App\Repositories\Interfaces\AuditRepositoryInterface;

class AuditService
{
    public function __construct(protected AuditRepositoryInterface $auditRepository, protected UserService $userService) {}

    public function getAll($filters = [], $perPage = 10)
    {
        return $this->auditRepository->getAll($filters, $perPage);
    }

    public function getAllWithoutPaginations($filters = [])
    {
        $user = null;

        if (! empty($filters['user_id'])) {
            $user = $this->userService->getById($filters['user_id']);
        }

        $logs = $this->auditRepository->getAllWithoutPaginations($filters);

        // Retornamos un arreglo con las dos variables
        return [
            'logs' => $logs,
            'user' => $user,
        ];
    }
}
