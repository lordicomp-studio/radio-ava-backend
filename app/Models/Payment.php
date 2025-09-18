<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payer_id',
        'track_id',
        'code',
        'price',
        'status',
        'gateway',
        'chosen_currency',
        'chosen_currency_price',
    ];

    protected $casts = [
        'status' => PaymentStatusEnum::class
    ];

    public function payer(){
        return $this->belongsTo(User::class, 'payer_id', 'id');
    }

    public function products(){
        return $this->morphedByMany(Product::class, 'paymentable')->withPivot(['id', 'parent_id']);
    }

    public function features(){
        return $this->morphedByMany(Feature::class, 'paymentable')->withPivot(['id', 'parent_id']);
    }

}
