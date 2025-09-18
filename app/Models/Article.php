<?php

namespace App\Models;

use App\Traits\IsViewable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Article extends Model
{
    use HasFactory, Sluggable, IsViewable, LogsActivity;

    protected $fillable = [
        'creator_id',
        'cover_id',
        'title',
        'slug',
        'description',
        'body',
        'published_at',
        'status',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()->logFillable();
    }

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function photo(){
        // TODO remove this and instead use cover() everywhere
        return $this->hasOne(Medium::class, 'id', 'cover_id');
    }

    public function cover(){
        return $this->hasOne(Medium::class, 'id', 'cover_id');
    }

    public function categories(){
        return $this->morphToMany(Category::class, 'catable');
    }

    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function rates()
    {
        return $this->morphMany(Rate::class, 'ratable');
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id', 'id');
    }

    public function views(){
        return $this->morphMany(View::class, 'viewable');
    }

    public function bookmarkers(){
        return $this->morphToMany(User::class, 'bookmarkables');
    }

}
