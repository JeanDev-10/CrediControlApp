<?php
namespace App\Services;

use App\Repositories\Interfaces\AuditRepositoryInterface;

class AuditService
{
    public function __construct(protected AuditRepositoryInterface $auditRepository) {}
    public function getAll($filters=[],$perPage=10)
    {
        return $this->auditRepository->getAll($filters,$perPage);
    }
    public function getAllWithoutPaginations($filters=[])
    {
        return $this->auditRepository->getAllWithoutPaginations($filters);
        // Lógica para obtener todos los registros de auditoría
    }
}