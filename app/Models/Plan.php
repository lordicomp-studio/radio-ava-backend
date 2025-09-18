<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{

    protected $fillable = [
        'creator_id',
        'duration',
        'price',
        'discount_percent',
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }

}
