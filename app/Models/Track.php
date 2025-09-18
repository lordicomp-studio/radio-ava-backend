<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Track extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'uploader_id',
        'artist_id',
        'album_id',
        'cover_id',
        'name',
        'slug',
        'duration',
        'is_mv',
        'lyrics_file_url',
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

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Medium::class, 'cover_id');
    }

    public function trackFiles()
    {
        return $this->hasMany(TrackFile::class);
    }

    public function categories(){
        return $this->morphToMany(Category::class, 'catable');
    }

    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }



}
