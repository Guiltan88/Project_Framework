<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Role constant
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_GUEST = 'guest';

    /**
     * Fillable fields
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'fakultas_id',
        'status',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* =====================================================
     | RELATIONS (ADMIN FOCUS)
     ===================================================== */

    /**
     * Admin dapat menyetujui banyak peminjaman
     */
    public function approvedPeminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'approved_by');
    }

    /**
     * Relasi ke fakultas (opsional)
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    /* =====================================================
     | ROLE CHECKERS (ADMIN UTAMA)
     ===================================================== */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isGuest(): bool
    {
        return $this->role === self::ROLE_GUEST;
    }

    /* =====================================================
     | QUERY SCOPES (ADMIN DASHBOARD)
     ===================================================== */

    /**
     * Ambil semua admin aktif
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * User aktif saja
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /* =====================================================
     | ACCESSORS (ADMIN DISPLAY)
     ===================================================== */

    /**
     * Label role untuk badge UI
     */
    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_STAFF => 'Staff',
            self::ROLE_GUEST => 'Guest',
            default => 'Unknown',
        };
    }

    /**
     * Warna badge role
     */
    public function getRoleBadgeAttribute()
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'danger',
            self::ROLE_STAFF => 'primary',
            self::ROLE_GUEST => 'secondary',
            default => 'dark',
        };
    }

    /**
     * Status badge
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'aktif' ? 'success' : 'secondary';
    }
}
