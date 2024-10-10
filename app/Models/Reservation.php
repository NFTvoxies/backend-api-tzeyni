<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['time','livrable_addresse','user_id','service_id','price','status'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class,'user_id');
    }

    public function service():BelongsTo{
        return $this->belongsTo(Service::class,'service_id');
    }
}
