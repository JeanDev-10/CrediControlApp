<?php

namespace App\Http\Requests\Contacts;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Maneja autorizaciones según tu app
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'min:10', 'max:10', 'regex:/^[0-9]+$/', Rule::unique('contacts')->where(fn ($query) =>
                $query->where('user_id', auth()->id())
            ),],
        ];
    }
}
