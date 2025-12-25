<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // RELATIONSHIP
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    // HELPER (sangat berguna)
    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role->name === 'staff';
    }

    public function isGuest(): bool
    {
        return $this->role->name === 'guest';
    }
}
