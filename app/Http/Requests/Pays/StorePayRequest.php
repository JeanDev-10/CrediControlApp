<?php

namespace App\Http\Requests\Pays;

use App\Models\Debt;
use App\Repositories\Interfaces\DebtRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StorePayRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'numeric', 'min:0.1'],
            'debt_id' => ['required', 'exists:debts,id'],
                    'date' => [
            'required',
            'date',
            'before_or_equal:today',
            function ($attribute, $value, $fail) {
                $debtRepository = App::make(DebtRepositoryInterface::class);
                $debtId = $this->input('debt_id');
                if (!is_numeric($debtId)) {
                    $fail('El ID de la deuda no es válido.');
                    return;
                }
                $debt = $debtRepository->find((int) $debtId);
                if (!$debt) {
                    $fail('La deuda especificada no existe.');
                    return;
                }
                if (Carbon::parse($value)->lt($debt->date_start)) {
                    $fail("La fecha del pago no puede ser anterior a la fecha de inicio de la deuda ({$debt->date_start->format('d/m/Y')}).");
                }
            },
        ],
            'images' => ['nullable', 'array', 'max:3'], // Limita el array a 3 elementos
            'images.*' => ['nullable', 'image', 'max:2048'], //valida cada imagen individualmente
        ];
    }
}
