<?php
// app/Models/Attendance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'working_hours',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    // Methods
    public function calculateWorkingHours()
    {
        if ($this->check_in && $this->check_out) {
            $start = \Carbon\Carbon::parse($this->check_in);
            $end = \Carbon\Carbon::parse($this->check_out);

            // Deduct 1 hour for lunch break if working more than 5 hours
            $hours = $start->diffInHours($end);
            if ($hours > 5) {
                $hours -= 1;
            }

            return round($hours, 2);
        }

        return 0;
    }

    public function isOnTime()
    {
        if (!$this->check_in) return false;

        $checkInTime = \Carbon\Carbon::parse($this->check_in);
        $officeStart = \Carbon\Carbon::parse('08:30');

        return $checkInTime->lte($officeStart);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'present' => 'success',
            'late' => 'warning',
            'absent' => 'danger',
            'sick' => 'info',
            'leave' => 'primary',
            'half_day' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'absent' => 'Tidak Hadir',
            'sick' => 'Sakit',
            'leave' => 'Cuti',
            'half_day' => 'Setengah Hari'
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
