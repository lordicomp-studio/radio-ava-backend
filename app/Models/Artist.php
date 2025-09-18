<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use Sluggable, HasFactory;

    protected $fillable = [
        'photo_id',
        'name',
        'slug',
        'description',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function photo()
    {
        return $this->belongsTo(Medium::class, 'photo_id');
    }

    public function categories(){
        return $this->morphToMany(Category::class, 'catable');
    }

    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }

}
