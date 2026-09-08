<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function boardingHouses()
    {
        return $this->hasMany(BoardingHouse::class, 'owner_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'student_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'student_id');
    }
}
