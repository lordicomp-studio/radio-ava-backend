<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Medium extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable=[
        'uploader_id',
        'name',
        'url',
        'size',
        'format',
    ];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()->logFillable();
    }

    public function uploader(){
        return $this->belongsTo(User::class, 'uploader_id', 'id');
    }

}
