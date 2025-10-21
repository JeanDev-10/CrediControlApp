<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function filter(array $filters, int $perPage = 10)
    {
        $query = $this->model->role('client'); // Utilizando el método 'role' para filtrar por rol
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['lastname'])) {
            $query->where('lastname', 'like', "%{$filters['lastname']}%");
        }
        if (! empty($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }
        // Aplicar filtro is_active solo si se ha pasado un valor válido
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']); // Ahora es un int, no string
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getAllForExport(array $filters = [])
    {
        $query = $this->model->role('client'); // Utilizando el método 'role' para filtrar por rol
        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['lastname'])) {
            $query->where('lastname', 'like', "%{$filters['lastname']}%");
        }
        if (! empty($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->get(); // 👈 sin paginar
    }

    public function toogleIsActive($id)
    {
        $user = $this->find($id);
        $user->is_active = ! $user->is_active; // Cambia de true a false o de false a true
        $user->save();
    }
}
