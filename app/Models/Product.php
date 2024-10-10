<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['name','description','brand','price','is_visible','is_featured','is_promo','promotion_price','professional_id'];
    
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function orders(): HasMany 
    {
        return $this->hasMany(Order::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commenteable');
    }
}
