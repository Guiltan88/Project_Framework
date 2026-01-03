<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'department',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Scope untuk mendapatkan user berdasarkan role (gunakan Spatie Permission)
     */
    public function scopeByRole($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Scope untuk mendapatkan user aktif (tidak soft deleted)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Helper methods untuk pengecekan role (menggunakan Spatie)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    public function isGuest(): bool
    {
        return $this->hasRole('guest');
    }

    /**
     * Get user's main role (first role)
     */
    public function getMainRoleAttribute(): string
    {
        return $this->roles->first()->name ?? 'guest';
    }

    /**
     * Get role name for display
     */
    public function getRoleNameAttribute(): string
    {
        return $this->roles->pluck('name')->join(', ');
    }

    /**
     * Booking yang dimiliki user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Staff profile relationship (hanya untuk user dengan role staff)
     */
    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function approvedBookings()
    {
        return $this->hasMany(Booking::class, 'approved_by');
    }
}
