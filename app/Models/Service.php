<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'time',
        'is_visible',
        'is_promo',
        'promotion_price',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function professional(): BelongsTo 
    {
        return $this->belongsTo(Professional::class);
    }

    public function reservations(): HasMany 
    {
        return $this->hasMany(Reservation::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commenteable');
    }
}
