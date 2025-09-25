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
            $query->where('type', $filters['name']);
        }
        if (! empty($filters['lastname'])) {
            $query->where('lastname', 'like', "%{$filters['lastname']}%");
        }
        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
