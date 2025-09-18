<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rate',
        'ratable_id',
        'ratable_type',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function ratable()
    {
        return $this->morphTo();
    }

}
