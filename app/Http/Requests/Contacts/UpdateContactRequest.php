<?php

namespace App\Http\Requests\Contacts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('contact'); // Ojo: debe coincidir con el parámetro de la ruta
        return [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:10',  'regex:/^[0-9]+$/', Rule::unique('contacts')
                ->where(fn($query) => $query->where('user_id', auth()->id()))
                ->ignore($id)],
        ];
    }
}
