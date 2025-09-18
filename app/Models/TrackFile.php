<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackFile extends Model
{

    protected $fillable = [
        'track_id',
        'quality',
        'file_url',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

}
