<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'medical_record_id',
        'appointment_id',
        'prescription_date',
        'medications',
        'dosage_instructions',
        'special_instructions',
        'notes',
        'status',
    ];

    protected $casts = [
        'prescription_date' => 'date',
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

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('prescription_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('prescription_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('prescription_date', now()->month)
                    ->whereYear('prescription_date', now()->year);
    }

    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getFormattedPrescriptionDateAttribute()
    {
        return $this->prescription_date->format('d M Y');
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'text-blue-600 bg-blue-100',
            'completed' => 'text-green-600 bg-green-100',
            'cancelled' => 'text-red-600 bg-red-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }
}
