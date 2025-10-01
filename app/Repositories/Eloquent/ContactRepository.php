<?php

namespace App\Repositories\Eloquent;

use App\Models\Contact;
use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    public function filter(array $filters, int $perPage = 10)
    {
        $query = $this->model->where('user_id', auth()->id());

        if (! empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }
        if (! empty($filters['lastname'])) {
            $query->where('lastname', 'like', "%{$filters['lastname']}%");
        }
        if (! empty($filters['contact_id'])) {
            $query->where('id',  $filters["contact_id"]);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getDebtsByContact($id, array $filters = [], $perPage = 10)
    {
        $contact = $this->model->find($id);
        $query = $contact->debts(); // Ya está filtrado por contacto

        if (! empty($filters['description'])) {
            $query->where('description', 'like', '%'.$filters['description'].'%');
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date_start'])) {
            $query->whereDate('date_start', '>=', $filters['date_start']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
