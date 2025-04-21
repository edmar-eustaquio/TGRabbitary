<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notification;

use App\Services\MustVerifyMobile;
use App\Services\IMustVerifyMobile;

class User extends Authenticatable implements IMustVerifyMobile
{
    use HasApiTokens, HasFactory, Notifiable, MustVerifyMobile;

    protected $fillable = [
        'name',
        'address',
        'phone_no',
        'email',
        'password',
        'last_online_at',
        'mobile_verified_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    
    public function routeNotificationForVonage(Notification $notification): string
    {
        return $this->phone_no;
    }

    public function user_messages(){
        return $this->hasMany(Message::class, 'user_id');
    }

    public function admin_messages(){
        return $this->hasMany(Message::class, 'admin_id');
    }

}
