<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Observers\UserObserver;
use App\Services\Interfaces\ImageServiceInterface;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::deleting(function (User $user) {
            // Eliminar las deudas y las imágenes asociadas
            foreach ($user->contacts as $contact) {
                // Eliminar las deudas asociadas al contacto
                foreach ($contact->debts as $debt) {
                    // Eliminar imágenes asociadas a los pagos de la deuda
                    foreach ($debt->pays as $pay) {
                        foreach ($pay->images as $image) {
                            // Obtener el servicio de imágenes
                            $imageService = app(ImageServiceInterface::class);
                            // Eliminar físicamente la imagen utilizando el UUID
                            $imageService->deleteImage($image->image_uuid);
                        }
                    }
                }
            }
        });
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
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value),
        );
    }

    public function contacts(){
        return $this->hasMany(Contact::class);
    }

}
