<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DoctorAttendance extends Model
{
    use HasFactory;

    protected $table = 'doctor_attendance';

    protected $fillable = [
        'doctor_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeHalfDay($query)
    {
        return $query->where('status', 'half_day');
    }

    // Helper methods
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'present' => 'Hadir',
            'early' => 'Awal',
            'late' => 'Terlambat',
            'absent' => 'Tidak Hadir',
            'izin' => 'Izin',
            'libur' => 'Libur',
            'half_day' => 'Setengah Hari',
            default => ucfirst($this->status)
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'present' => 'green',
            'early' => 'emerald',
            'late' => 'yellow',
            'absent' => 'red',
            'izin' => 'orange',
            'libur' => 'purple',
            'half_day' => 'blue',
            default => 'gray'
        };
    }

    public function getWorkingHoursAttribute()
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in_time);
        $checkOut = Carbon::parse($this->check_out_time);
        
        return $checkIn->diffInHours($checkOut, true);
    }

    public function isCheckedIn()
    {
        return !is_null($this->check_in_time);
    }

    public function isCheckedOut()
    {
        return !is_null($this->check_out_time);
    }

    public function canCheckOut()
    {
        return $this->isCheckedIn() && !$this->isCheckedOut();
    }

    public function isLate()
    {
        if (!$this->check_in_time) {
            return false;
        }

        $checkInTime = Carbon::parse($this->check_in_time);
        $expectedTime = Carbon::createFromTime(8, 0, 0); // 8:00 AM

        return $checkInTime->gt($expectedTime);
    }
}
