<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'downloader_id',
        'downloadable_id',
        'downloadable_type',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'downloader_id', 'id');
    }

    public function downloadable(){
        return $this->morphTo();
    }

}
