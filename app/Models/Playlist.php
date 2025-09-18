<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'creator_id',
        'name',
        'slug',
        'photo_id',
        'is_published',
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

    public function photo(){
        return $this->belongsTo(Medium::class, 'photo_id');
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'playlist_tracks', 'track_id', 'playlist_id');
    }

    public function categories(){
        return $this->morphToMany(Category::class, 'catable');
    }

    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }

}
