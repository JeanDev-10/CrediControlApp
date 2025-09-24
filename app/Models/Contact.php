<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            // 👀 Al obtener el valor → primera letra en mayúscula
            get: fn ($value) => ucwords($value),

            // 👀 Al guardar en la BD → en minúsculas
            set: fn ($value) => strtolower($value),
        );
    }
    protected function lastname(): Attribute
    {
        return Attribute::make(
            // 👀 Al obtener el valor → primera letra en mayúscula
            get: fn ($value) => ucwords($value),

            // 👀 Al guardar en la BD → en minúsculas
            set: fn ($value) => strtolower($value),
        );
    }
}
