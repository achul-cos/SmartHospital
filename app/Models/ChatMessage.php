<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_type',
        'sender_id',
        'message',
        'message_type',
        'file_path',
        'file_name',
        'is_read',
        'message_status',
        'delivered_at',
        'read_at',
        'is_typing',
        'typing_started_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_typing' => 'boolean',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'typing_started_at' => 'datetime'
    ];

    // Relationships
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'sender_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'sender_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'sender_id');
    }

    public function sender()
    {
        switch ($this->sender_type) {
            case 'admin':
                return $this->belongsTo(Admin::class, 'sender_id');
            case 'doctor':
                return $this->belongsTo(Doctor::class, 'sender_id');
            case 'patient':
                return $this->belongsTo(Patient::class, 'sender_id');
            default:
                return null;
        }
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $messageType)
    {
        return $query->where('message_type', $messageType);
    }

    public function scopeBySender($query, $senderType, $senderId)
    {
        return $query->where('sender_type', $senderType)->where('sender_id', $senderId);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Helper methods
    public function markAsDelivered()
    {
        $this->update([
            'message_status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    public function markAsRead()
    {
        $this->update([
            'message_status' => 'read',
            'read_at' => now(),
            'is_read' => true
        ]);
    }

    public function isDelivered()
    {
        return $this->message_status === 'delivered' || $this->message_status === 'read';
    }

    public function isRead()
    {
        return $this->message_status === 'read';
    }

    public function getStatusIconAttribute()
    {
        switch ($this->message_status) {
            case 'sent':
                return '<svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            case 'delivered':
                return '<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            case 'read':
                return '<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            default:
                return '<svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
        }
    }

    public function getStatusTextAttribute()
    {
        switch ($this->message_status) {
            case 'sent':
                return 'Terkirim';
            case 'delivered':
                return 'Diterima';
            case 'read':
                return 'Dibaca';
            default:
                return 'Mengirim...';
        }
    }

    public function isFromUser($userType, $userId)
    {
        return $this->sender_type === $userType && $this->sender_id === $userId;
    }

    public function isFromAdmin()
    {
        return $this->sender_type === 'admin';
    }

    public function isFromDoctor()
    {
        return $this->sender_type === 'doctor';
    }

    public function isFromPatient()
    {
        return $this->sender_type === 'patient';
    }

    public function isSystemMessage()
    {
        return $this->sender_type === 'system';
    }

    public function hasAttachment()
    {
        return !empty($this->file_path);
    }

    public function getAttachmentUrl()
    {
        if ($this->hasAttachment()) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    // Accessors
    public function getSenderNameAttribute()
    {
        if ($this->sender_type === 'system') {
            return 'System';
        }
        
        // Try to get sender name based on sender_type
        switch ($this->sender_type) {
            case 'admin':
                $admin = Admin::find($this->sender_id);
                return $admin ? $admin->name : 'Admin';
            case 'doctor':
                $doctor = Doctor::find($this->sender_id);
                return $doctor ? $doctor->name : 'Doctor';
            case 'patient':
                $patient = Patient::find($this->sender_id);
                return $patient ? $patient->name : 'Patient';
            default:
                return 'Unknown';
        }
    }

    public function getSenderAvatarAttribute()
    {
        if ($this->sender) {
            return $this->sender->name[0] ?? '?';
        }
        return 'S';
    }

    public function getSenderColorAttribute()
    {
        return match($this->sender_type) {
            'admin' => 'bg-blue-500',
            'doctor' => 'bg-green-500',
            'patient' => 'bg-purple-500',
            default => 'bg-gray-500'
        };
    }

    public function getMessageTypeTextAttribute()
    {
        return match($this->message_type) {
            'text' => 'Teks',
            'image' => 'Gambar',
            'file' => 'File',
            'system' => 'Sistem',
            default => ucfirst($this->message_type)
        };
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    public function getShortMessageAttribute()
    {
        return \Str::limit($this->message, 100);
    }
}
