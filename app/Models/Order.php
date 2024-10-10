<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['quantity','livrable_addresse','user_id','product_id','price','status'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class,'user_id');
    }

    public function product():BelongsTo{
        return $this->belongsTo(Product::class,'product_id');
    }
}
