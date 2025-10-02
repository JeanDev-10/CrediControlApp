<?php

namespace App\Models;

use App\Policies\ImagePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
#[UsePolicy(ImagePolicy::class)]
class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;
    protected $fillable = ['image_uuid', 'url', 'pay_id'];
    public function pay()
    {
        return $this->belongsTo(Pay::class);
    }
}
