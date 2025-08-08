<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'status',
        'max_concurrent_chats',
        'current_chats',
        'specializations',
        'last_activity_at',
    ];

    protected $casts = [
        'specializations' => 'array',
        'last_activity_at' => 'datetime',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'admin_id');
    }

    public function activeChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'admin_id')->where('status', 'active');
    }

    // Scopes
    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeBusy($query)
    {
        return $query->where('status', 'busy');
    }

    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'online')
                    ->where('current_chats', '<', 'max_concurrent_chats');
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->whereJsonContains('specializations', $specialization);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'online' && $this->current_chats < $this->max_concurrent_chats;
    }

    public function canAcceptChat()
    {
        return $this->isAvailable();
    }

    public function acceptChat()
    {
        if ($this->canAcceptChat()) {
            $this->increment('current_chats');
            $this->update(['last_activity_at' => now()]);
            return true;
        }
        return false;
    }

    public function releaseChat()
    {
        if ($this->current_chats > 0) {
            $this->decrement('current_chats');
            $this->update(['last_activity_at' => now()]);
            return true;
        }
        return false;
    }

    public function setStatus($status)
    {
        $this->update([
            'status' => $status,
            'last_activity_at' => now()
        ]);
    }

    public function goOnline()
    {
        $this->setStatus('online');
    }

    public function goBusy()
    {
        $this->setStatus('busy');
    }

    public function goOffline()
    {
        $this->setStatus('offline');
    }

    public function updateActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function getWorkloadPercentage()
    {
        if ($this->max_concurrent_chats > 0) {
            return round(($this->current_chats / $this->max_concurrent_chats) * 100);
        }
        return 0;
    }

    public function getWorkloadColor()
    {
        $percentage = $this->getWorkloadPercentage();
        
        if ($percentage >= 80) {
            return 'text-red-600';
        } elseif ($percentage >= 60) {
            return 'text-orange-600';
        } elseif ($percentage >= 40) {
            return 'text-yellow-600';
        } else {
            return 'text-green-600';
        }
    }

    public function addSpecialization($specialization)
    {
        $specializations = $this->specializations ?? [];
        if (!in_array($specialization, $specializations)) {
            $specializations[] = $specialization;
            $this->update(['specializations' => $specializations]);
        }
    }

    public function removeSpecialization($specialization)
    {
        $specializations = $this->specializations ?? [];
        $specializations = array_diff($specializations, [$specialization]);
        $this->update(['specializations' => array_values($specializations)]);
    }

    public function hasSpecialization($specialization)
    {
        return in_array($specialization, $this->specializations ?? []);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'online' => 'Online',
            'busy' => 'Sibuk',
            'offline' => 'Offline',
            default => ucfirst($this->status)
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'online' => 'bg-green-100 text-green-800',
            'busy' => 'bg-yellow-100 text-yellow-800',
            'offline' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'online' => 'M5 13l4 4L19 7',
            'busy' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'offline' => 'M6 18L18 6M6 6l12 12',
            default => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }

    public function getAvailabilityTextAttribute()
    {
        if ($this->status === 'offline') {
            return 'Tidak Tersedia';
        }
        
        if ($this->current_chats >= $this->max_concurrent_chats) {
            return 'Penuh';
        }
        
        return 'Tersedia';
    }

    public function getLastActivityTextAttribute()
    {
        if ($this->last_activity_at) {
            return $this->last_activity_at->diffForHumans();
        }
        return 'Tidak diketahui';
    }

    public function getSpecializationsTextAttribute()
    {
        if (empty($this->specializations)) {
            return 'Umum';
        }
        return implode(', ', $this->specializations);
    }
}
