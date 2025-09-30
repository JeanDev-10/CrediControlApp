<?php

namespace App\Http\Requests\Pays;

class UpdatePayRequest extends StorePayRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        // Obtener el modelo actual (asumiendo que la ruta tiene el parámetro 'pay')
        $pay = $this->route('pay');
        if ($pay->images()->exists()) {
        $existingImagesCount = $pay->images()->count();
        $maxNewImages = max(0, 3 - $existingImagesCount);
        if ($maxNewImages > 0) {
            $rules['images'] = ['nullable', 'array', 'max:'.$maxNewImages];
            $rules['images.*'] = ['nullable', 'image', 'max:2048'];
        } else {
            $rules['images'] = ['prohibited'];
        }
        }
        return $rules;
    }
}
