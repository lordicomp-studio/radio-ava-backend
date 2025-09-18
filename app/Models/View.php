<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'device',
        'platform',
        'browser',
        'viewable_id',
        'viewable_type',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function viewable(){
        return $this->morphTo();
    }

}
