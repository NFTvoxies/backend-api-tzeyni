<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['body','commenteable_id','commenteable_type','user_id'];
    
    public function commenteable(): MorphTo
    {
        return $this->morphTo();
    }
    public function user(): HasMany
    {
        return $this->hasMany(User::class,'user_id');
    }
}
