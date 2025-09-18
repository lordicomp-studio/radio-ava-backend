<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'creator_id',
        'artist_id',
        'photo_id',
        'name',
        'slug',
        'is_published',
        'release_date',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function artist(){
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function photo(){
        return $this->belongsTo(Medium::class, 'photo_id');
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'album_tracks', 'album_id', 'track_id');
    }

}
