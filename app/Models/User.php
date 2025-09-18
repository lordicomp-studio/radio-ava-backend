<?php

namespace App\Models;

use App\Enums\UserLevelEnum;
use App\Notifications\Api\v1\EmailVerificationNotification;
use App\Notifications\Api\v1\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use LogsActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'bio',
        'profile_photo_id',
        'level',
        'password',
    ];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()->logFillable();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'level' => UserLevelEnum::class,
    ];

    public function uploadedMedia(){
        return $this->hasMany(Medium::class, 'uploader_id', 'id');
    }

    public function profilePhoto(){
        return $this->hasOne(Medium::class, 'id', 'profile_photo_id');
    }

    public function pages(){
        return $this->hasMany(Page::class, 'creator_id', 'id');
    }

    public function articles(){
        return $this->hasMany(Article::class, 'creator_id', 'id');
    }

    public function products(){
        return $this->hasMany(Product::class, 'creator_id', 'id');
    }

    public function productEditRequests(){
        return $this->hasMany(ProductEditRequest::class, 'creator_id', 'id');
    }

    /* override */
    public function sendPasswordResetNotification($token)
    {
        $clientDomAddress = env('CLIENT_DOMAIN_ADDRESS');
        $url = $clientDomAddress . "/resetPassword?token=$token";
        $this->notify(new ResetPasswordNotification($url));
    }

    /* override */
    public function sendEmailVerificationNotification(){
        $this->notify(new EmailVerificationNotification($this->id));
    }

    public function rates(){
        return $this->hasMany(Rate::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'payer_id', 'id');
    }

    public function bookmarkedArticles(){
        return $this->morphedByMany(Article::class, 'bookmarkables');
    }

    public function bookmarkedProducts(){
        return $this->morphedByMany(Product::class, 'bookmarkables');
    }

}
