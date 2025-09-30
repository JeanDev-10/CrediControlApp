<?php

namespace App\Models;

use App\Policies\PayPolicy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(PayPolicy::class)]
class Pay extends Model
{
    /** @use HasFactory<\Database\Factories\PayFactory> */
    use HasFactory;
    protected $fillable = ['quantity', 'date', 'debt_id'];

    protected $casts = [
        'date' => 'date',
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
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
