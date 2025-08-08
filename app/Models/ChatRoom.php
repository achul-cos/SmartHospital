<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_type',
        'user_id',
        'admin_id',
        'status',
        'subject',
        'priority',
        'last_message_at',
        'assigned_at',
        'closed_at',
        'typing_users',
        'last_typing_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_message_at' => 'datetime',
        'typing_users' => 'array',
        'last_typing_at' => 'datetime'
    ];

    // Relationships
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id');
    }

    public function user()
    {
        switch ($this->user_type) {
            case 'doctor':
                return $this->belongsTo(Doctor::class, 'user_id');
            case 'patient':
                return $this->belongsTo(Patient::class, 'user_id');
            default:
                return null;
        }
    }

    // Scopes
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByUser($query, $userType, $userId)
    {
        return $query->where('user_type', $userType)->where('user_id', $userId);
    }

    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Helper methods
    public static function generateRoomId()
    {
        return 'room_' . Str::random(16);
    }

    public function assignAdmin($adminId = null)
    {
        if ($adminId) {
            $this->update([
                'admin_id' => $adminId,
                'status' => 'active',
                'assigned_at' => now()
            ]);
        } else {
            // Auto-assign available admin
            $availableAdmin = AdminAssignment::where('status', 'online')
                ->where('current_chats', '<', 'max_concurrent_chats')
                ->orderBy('current_chats', 'asc')
                ->first();

            if ($availableAdmin) {
                $this->update([
                    'admin_id' => $availableAdmin->admin_id,
                    'status' => 'active',
                    'assigned_at' => now()
                ]);

                // Update admin's current chat count
                $availableAdmin->increment('current_chats');
            }
        }
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now()
        ]);

        // Decrease admin's current chat count
        if ($this->admin_id) {
            AdminAssignment::where('admin_id', $this->admin_id)->decrement('current_chats');
        }
    }

    public function addMessage($senderType, $senderId, $message, $messageType = 'text', $filePath = null, $fileName = null)
    {
        $chatMessage = $this->messages()->create([
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $message,
            'message_type' => $messageType,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        // Update last message timestamp
        $this->update(['last_message_at' => now()]);

        return $chatMessage;
    }

    public function getUnreadCount($userId, $userType)
    {
        return $this->messages()
            ->where('sender_type', '!=', $userType)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead($userId, $userType)
    {
        $this->messages()
            ->where('sender_type', '!=', $userType)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    // Advanced features for scalability
    public function getUnreadCountForUser($userId, $userType)
    {
        return $this->messages()
            ->where('sender_type', '!=', $userType)
            ->where('is_read', false)
            ->count();
    }

    public function getLastActivityAttribute()
    {
        $lastMessage = $this->messages()->latest()->first();
        return $lastMessage ? $lastMessage->created_at : $this->created_at;
    }

    public function getResponseTimeAttribute()
    {
        $firstMessage = $this->messages()->where('sender_type', 'doctor')->first();
        $firstAdminMessage = $this->messages()->where('sender_type', 'admin')->first();
        
        if ($firstMessage && $firstAdminMessage && $firstAdminMessage->created_at > $firstMessage->created_at) {
            return $firstMessage->created_at->diffInMinutes($firstAdminMessage->created_at);
        }
        
        return null;
    }

    public function getAverageResponseTimeAttribute()
    {
        $doctorMessages = $this->messages()->where('sender_type', 'doctor')->get();
        $adminMessages = $this->messages()->where('sender_type', 'admin')->get();
        
        $responseTimes = [];
        
        foreach ($doctorMessages as $doctorMsg) {
            $nextAdminMsg = $adminMessages->where('created_at', '>', $doctorMsg->created_at)->first();
            if ($nextAdminMsg) {
                $responseTimes[] = $doctorMsg->created_at->diffInMinutes($nextAdminMsg->created_at);
            }
        }
        
        return !empty($responseTimes) ? round(array_sum($responseTimes) / count($responseTimes), 1) : null;
    }

    public function getMessageCountAttribute()
    {
        return $this->messages()->count();
    }

    public function getDoctorMessageCountAttribute()
    {
        return $this->messages()->where('sender_type', 'doctor')->count();
    }

    public function getAdminMessageCountAttribute()
    {
        return $this->messages()->where('sender_type', 'admin')->count();
    }

    public function isStale($hours = 24)
    {
        return $this->last_activity_at && $this->last_activity_at->diffInHours(now()) > $hours;
    }

    public function needsFollowUp()
    {
        return $this->status === 'waiting' && $this->created_at->diffInHours(now()) > 2;
    }

    public function canBeEscalated()
    {
        return $this->status === 'waiting' && $this->created_at->diffInHours(now()) > 4;
    }

    public function escalate()
    {
        // Find available admin with lowest workload
        $availableAdmin = AdminAssignment::where('status', 'online')
            ->where('current_chats', '<', 'max_concurrent_chats')
            ->orderBy('current_chats', 'asc')
            ->first();

        if ($availableAdmin) {
            $this->update([
                'admin_id' => $availableAdmin->admin_id,
                'status' => 'active',
                'assigned_at' => now()
            ]);

            // Add escalation message
            $this->addMessage(
                'system',
                0,
                "Chat telah di-escalate ke admin {$availableAdmin->admin->name} karena waktu tunggu yang lama.",
                'system'
            );

            return true;
        }

        return false;
    }

    public function getSatisfactionScore()
    {
        // This would be implemented when we add rating system
        return null;
    }

    public function getTagsAttribute()
    {
        $tags = [];
        
        if ($this->priority === 'urgent') {
            $tags[] = 'urgent';
        }
        
        if ($this->isStale()) {
            $tags[] = 'stale';
        }
        
        if ($this->needsFollowUp()) {
            $tags[] = 'needs-followup';
        }
        
        if ($this->canBeEscalated()) {
            $tags[] = 'escalation-needed';
        }
        
        return $tags;
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'waiting' => 'Menunggu Admin',
            'active' => 'Sedang Berlangsung',
            'closed' => 'Selesai',
            default => ucfirst($this->status)
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'waiting' => 'bg-yellow-100 text-yellow-800',
            'active' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPriorityTextAttribute()
    {
        return match($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak',
            default => ucfirst($this->priority)
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'urgent' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getDurationAttribute()
    {
        if ($this->closed_at) {
            return $this->created_at->diffForHumans($this->closed_at, true);
        }
        return $this->created_at->diffForHumans();
    }

    public function getLastMessageAttribute()
    {
        $lastMessage = $this->messages()->latest()->first();
        if ($lastMessage) {
            return \Str::limit($lastMessage->message, 50);
        }
        return 'Belum ada pesan';
    }

    // Typing indicator methods
    public function startTyping($userId, $userType)
    {
        $typingUsers = $this->typing_users ?? [];
        $typingUsers[$userType . '_' . $userId] = [
            'user_id' => $userId,
            'user_type' => $userType,
            'started_at' => now()->toISOString()
        ];
        
        $this->update([
            'typing_users' => $typingUsers,
            'last_typing_at' => now()
        ]);
    }

    public function stopTyping($userId, $userType)
    {
        $typingUsers = $this->typing_users ?? [];
        $key = $userType . '_' . $userId;
        
        if (isset($typingUsers[$key])) {
            unset($typingUsers[$key]);
        }
        
        $this->update([
            'typing_users' => $typingUsers,
            'last_typing_at' => !empty($typingUsers) ? now() : null
        ]);
    }

    public function getTypingUsers($excludeUserId = null, $excludeUserType = null)
    {
        $typingUsers = $this->typing_users ?? [];
        $result = [];
        
        foreach ($typingUsers as $key => $user) {
            if ($excludeUserId && $excludeUserType && 
                $user['user_id'] == $excludeUserId && $user['user_type'] == $excludeUserType) {
                continue;
            }
            
            // Remove old typing indicators (older than 10 seconds)
            $startedAt = \Carbon\Carbon::parse($user['started_at']);
            if ($startedAt->diffInSeconds(now()) > 10) {
                unset($typingUsers[$key]);
                continue;
            }
            
            $result[] = $user;
        }
        
        // Update if we removed old entries
        if (count($typingUsers) !== count($this->typing_users ?? [])) {
            $this->update(['typing_users' => $typingUsers]);
        }
        
        return $result;
    }

    public function isAnyoneTyping($excludeUserId = null, $excludeUserType = null)
    {
        return count($this->getTypingUsers($excludeUserId, $excludeUserType)) > 0;
    }

    public function getTypingIndicatorText($excludeUserId = null, $excludeUserType = null)
    {
        $typingUsers = $this->getTypingUsers($excludeUserId, $excludeUserType);
        
        if (empty($typingUsers)) {
            return null;
        }
        
        $names = [];
        foreach ($typingUsers as $user) {
            switch ($user['user_type']) {
                case 'admin':
                    $admin = Admin::find($user['user_id']);
                    $names[] = $admin ? $admin->name : 'Admin';
                    break;
                case 'doctor':
                    $doctor = Doctor::find($user['user_id']);
                    $names[] = $doctor ? $doctor->name : 'Dokter';
                    break;
                default:
                    $names[] = 'User';
            }
        }
        
        if (count($names) === 1) {
            return $names[0] . ' sedang mengetik...';
        } else {
            return implode(', ', array_slice($names, 0, -1)) . ' dan ' . end($names) . ' sedang mengetik...';
        }
    }
}
