<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Page extends Model
{
    use HasFactory, Sluggable, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'creator_id',
        'medium_id',
        'description',
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

    public function photo(){
        return $this->hasOne(Medium::class, 'id', 'medium_id');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
