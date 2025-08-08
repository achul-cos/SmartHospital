<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'specialization',
        'license_number',
        'experience_years',
        'address',
        'status',
        'email_notifications',
        'sms_notifications',
        'chat_notifications',
        'appointment_notifications',
        'late_tolerance_minutes',
        'deactivated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deactivated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'chat_notifications' => 'boolean',
        'appointment_notifications' => 'boolean',
    ];

    // Relationships
    public function attendances()
    {
        return $this->hasMany(DoctorAttendance::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function attendance()
    {
        return $this->hasMany(DoctorAttendance::class);
    }

    public function todayAttendance()
    {
        return $this->hasOne(DoctorAttendance::class)->whereDate('date', today());
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('attendance', function($q) {
            $q->whereDate('date', today())->where('status', 'present');
        });
    }
}
