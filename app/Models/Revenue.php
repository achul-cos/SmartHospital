<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $table = 'revenue';

    protected $fillable = [
        'date',
        'total_revenue',
        'appointment_count',
        'consultation_fees',
        'medication_fees',
        'procedure_fees',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_revenue' => 'decimal:2',
        'consultation_fees' => 'decimal:2',
        'medication_fees' => 'decimal:2',
        'procedure_fees' => 'decimal:2',
    ];

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
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

    public function scopeLastMonth($query)
    {
        return $query->whereMonth('date', now()->subMonth()->month)
                    ->whereYear('date', now()->subMonth()->year);
    }
}
