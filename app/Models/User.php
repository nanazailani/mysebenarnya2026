<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'verification_code',
        'is_first_login',
        'role',
        'agency_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_first_login' => 'boolean',
    ];

    // app/Models/User.php

    public function progressUpdates()
    {
        return $this->hasMany(\App\Models\InquiryProgress::class, 'updated_by');
    }

}
