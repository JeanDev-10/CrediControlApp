<?php

namespace App\Models;

use App\Observers\DebtObserver;
use App\Policies\DebtPolicy;
use App\Services\Interfaces\ImageServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(DebtPolicy::class)]
#[ObservedBy([DebtObserver::class])]

class Debt extends Model
{
    /** @use HasFactory<\Database\Factories\DebtFactory> */
    use HasFactory;

    protected $fillable = [
        'quantity',
        'description',
        'date_start',
        'status',
        'contact_id',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'float',
        'date_start' => 'date',
    ];

    protected static function booted()
    {
        static::deleting(function (Debt $debt) {
            // Eliminar imágenes asociadas a los pagos del debt
            foreach ($debt->pays as $pay) {
                foreach ($pay->images as $image) {
                    $imageService = app(ImageServiceInterface::class);
                    $imageService->deleteImage($image->image_uuid);
                }
            }
        });
    }

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function pays()
    {
        return $this->hasMany(Pay::class);
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
    /* protected function createdAt(): Attribute
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
    } */
}
