<?php

namespace App\Models\Module01_IAM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'department', 
        'position', 'phone', 'status', 'last_login_at', 'email_verified_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function mfaTokens()
    {
        return $this->hasMany(MfaToken::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }
}