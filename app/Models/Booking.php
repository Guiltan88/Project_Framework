<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_booking',
        'user_id',
        'room_id',
        'tujuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_peserta',
        'kebutuhan_khusus',
        'catatan',
        'status',
        'approved_by',
        'approved_at',
        'alasan_penolakan',
        'cancelled_by',
        'cancelled_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_mulai', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>=', now())
                    ->where('status', self::STATUS_APPROVED);
    }

    // Methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // Format waktu untuk display
    public function getTimeRangeAttribute()
    {
        return $this->waktu_mulai . ' - ' . $this->waktu_selesai;
    }

    public function getDateRangeAttribute()
    {
        if ($this->tanggal_mulai->equalTo($this->tanggal_selesai)) {
            return $this->tanggal_mulai->format('d M Y');
        }
        return $this->tanggal_mulai->format('d M') . ' - ' . $this->tanggal_selesai->format('d M Y');
    }
}
