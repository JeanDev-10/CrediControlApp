<?php

namespace App\Models;

use App\Observers\ContactObserver;
use App\Policies\ContactPolicy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(ContactPolicy::class)]
#[ObservedBy([ContactObserver::class])]
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
    public function debts()
    {
        return $this->HasMany(Debt::class);
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
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d/m/Y H:i'),
        );
    }
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d/m/Y H:i'),
        );
    }
}
