<?php

namespace App\Http\Requests\Debts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy controlará si puede
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'date_start' => 'required|date',
            'contact_id' => 'required|exists:contacts,id',
            'status' => 'nullable|in:pendiente,pagada',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $debt = $this->route('debt');

            if (!$debt) {
                return;
            }

            $paidAmount = $debt->pays()->sum('quantity');
            $newQuantity = (float) $this->input('quantity');

            if ($newQuantity < $paidAmount) {
                $validator->errors()->add(
                    'quantity',
                    "La cantidad no puede ser menor a lo ya pagado ($paidAmount)."
                );
            }
        });
    }
}
