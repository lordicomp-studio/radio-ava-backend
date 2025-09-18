<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends Model
{
    use HasFactory, Sluggable, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'user_id',
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
                'source' => 'name'
            ]
        ];
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()->logFillable();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function articles(){
        return $this->morphedByMany(Article::class, 'taggable');
    }

    public function products(){
        return $this->morphedByMany(Product::class, 'taggable');
    }

}
