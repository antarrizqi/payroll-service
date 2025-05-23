<?php


// app/Models/User.php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika menggunakan Sanctum untuk API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis hash password
    ];

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    // Di dalam App\Models\Karyawan.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }
}
