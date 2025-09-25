<?php

namespace App\Models;

use App\Policies\TransactionPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(TransactionPolicy::class)]
class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'description',
        'type',
        'quantity',
        'previus_quantity',
        'after_quantity', 'user_id',
    ];

    protected $casts = [
        'quantity' => 'float',
        'previus_quantity' => 'float',
        'after_quantity' => 'float',
        'type' => 'string', // enum: income, expense
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            // 👀 Al obtener el valor → primera letra en mayúscula
            get: fn ($value) => ucfirst($value),

            // 👀 Al guardar en la BD → en minúsculas
            set: fn ($value) => strtolower($value),
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            // 👀 Al obtener el valor → primera letra en mayúscula
            get: fn ($value) => ucfirst($value),

            // 👀 Al guardar en la BD → en minúsculas
            set: fn ($value) => strtolower($value),
        );
    }
}
