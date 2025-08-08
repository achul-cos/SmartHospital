<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'record_date',
        'chief_complaint',
        'physical_examination',
        'diagnosis',
        'treatment_plan',
        'notes',
        'blood_pressure',
        'temperature',
        'pulse_rate',
        'weight',
        'height',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('record_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('record_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('record_date', now()->month)
                    ->whereYear('record_date', now()->year);
    }

    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    // Accessors
    public function getFormattedRecordDateAttribute()
    {
        return $this->record_date->format('d M Y');
    }

    public function getAgeAttribute()
    {
        return $this->record_date->diffInYears(now());
    }
}
