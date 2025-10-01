<?php

namespace App\Repositories\Interfaces;

interface ContactRepositoryInterface extends BaseRepositoryInterface
{
    // Aquí podrías agregar métodos específicos de Contact si los necesitas
    public function filter(array $filters, int $perPage = 10);
    public function getDebtsByContact($id, array $filters = [], $perPage = 10);
    public function getAllForExport(array $filters = []);
}
