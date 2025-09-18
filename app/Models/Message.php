<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Message extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'id',
        'chat_id',
        'sender_id',
        'message',
        'file_url',
        'seen_at',
        'reply_to_id',
    ];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()->logFillable();
    }

    protected $casts = [
        'seen_at' => 'datetime',
    ];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function chat(){
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

}
