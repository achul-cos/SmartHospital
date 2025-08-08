<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'action',
        'description',
        'ip_address',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        switch ($this->user_type) {
            case 'admin':
                return $this->belongsTo(Admin::class, 'user_id');
            case 'doctor':
                return $this->belongsTo(Doctor::class, 'user_id');
            case 'patient':
                return $this->belongsTo(Patient::class, 'user_id');
            default:
                return null;
        }
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    // Helper method to create activity log
    public static function log($userType, $userId, $action, $description, $metadata = null, $ipAddress = null)
    {
        return self::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }
}
