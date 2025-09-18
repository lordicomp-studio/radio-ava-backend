<?php

namespace App\Models;

use App\Enums\ChatPriorityEnum;
use App\Enums\ChatTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Chat extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'priority',
        'status',
        'chat_type',
    ];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()->logFillable();
    }

    protected $casts = [
        'priority' => ChatPriorityEnum::class,
        'chat_type' => ChatTypeEnum::class,
    ];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function messages(): HasMany{
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

}
