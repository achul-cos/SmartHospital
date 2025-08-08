<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\AdminAssignment;
use Carbon\Carbon;

class DoctorsChatController extends Controller
{
    public function index()
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            if (!$doctor) {
                \Log::error('No doctor authenticated in chat index');
                return redirect()->route('doctors.login');
            }
            
            \Log::info('Chat index accessed by doctor: ' . $doctor->name);
            
            // Auto-escalate stale chats
            $this->autoEscalateStaleChats();
            
            // Get all chat rooms for this doctor
            $chatRooms = ChatRoom::where('user_type', 'doctor')
                ->where('user_id', $doctor->id)
                ->with(['admin', 'messages' => function($query) {
                    $query->latest()->limit(1);
                }])
                ->orderByRaw('COALESCE(last_message_at, created_at) DESC')
                ->get();
            
            \Log::info('Found ' . $chatRooms->count() . ' chat rooms for doctor');
            
            // Get available admins
            $availableAdmins = AdminAssignment::available()
                ->with('admin')
                ->get();
            
            \Log::info('Found ' . $availableAdmins->count() . ' available admins');
            
            // Get chat statistics
            $chatStats = $this->getChatStatistics($doctor->id);
            
            return view('doctors.dashboard.hubungi-admin', compact('chatRooms', 'availableAdmins', 'chatStats'));
            
        } catch (\Exception $e) {
            \Log::error('Error in chat index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($roomId)
    {
        $doctor = Auth::guard('doctor')->user();
        
        $chatRoom = ChatRoom::where('room_id', $roomId)
            ->where('user_type', 'doctor')
            ->where('user_id', $doctor->id)
            ->with(['admin', 'messages' => function($query) {
                $query->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();
        
        // Mark messages as read
        $chatRoom->markAsRead($doctor->id, 'doctor');
        
        return view('doctors.dashboard.chat-room', compact('chatRoom'));
    }
    
    public function create(Request $request)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $request->validate([
                'subject' => 'required|string|max:255',
                'priority' => 'required|in:low,medium,high,urgent',
                'initial_message' => 'required|string|max:1000'
            ]);
            
            // Create chat room
            $chatRoom = ChatRoom::create([
                'room_id' => ChatRoom::generateRoomId(),
                'user_type' => 'doctor',
                'user_id' => $doctor->id,
                'status' => 'waiting',
                'subject' => $request->subject,
                'priority' => $request->priority
            ]);
            
            // Add initial message
            $chatRoom->addMessage(
                'doctor',
                $doctor->id,
                $request->initial_message,
                'text'
            );
            
            // Add system message
            $chatRoom->addMessage(
                'system',
                0,
                'Pesan Anda telah diterima. Admin akan segera menghubungi Anda.',
                'system'
            );
            
            // Try to assign admin
            $chatRoom->assignAdmin();
            
            return redirect()->route('doctors.dashboard.hubungi-admin')
                ->with('success', 'Chat baru berhasil dibuat. Admin akan segera menghubungi Anda.');
                
        } catch (\Exception $e) {
            \Log::error('Error creating chat: ' . $e->getMessage());
            return redirect()->route('doctors.dashboard.hubungi-admin')
                ->with('error', 'Gagal membuat chat: ' . $e->getMessage());
        }
    }
    
    public function sendMessage(Request $request, $roomId)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $request->validate([
                'message' => 'required|string|max:1000'
            ]);
            
            $chatRoom = ChatRoom::where('room_id', $roomId)
                ->where('user_type', 'doctor')
                ->where('user_id', $doctor->id)
                ->firstOrFail();
            
            // Stop typing indicator
            $chatRoom->stopTyping($doctor->id, 'doctor');
            
            // Add message
            $message = $chatRoom->addMessage(
                'doctor',
                $doctor->id,
                $request->message,
                'text'
            );
            
            // Mark as delivered after a short delay (simulating delivery)
            \Log::info("Message sent: {$message->id}");
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'message_id' => $message->id,
                'status' => 'sent'
            ]);
                
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getMessages($roomId)
    {
        try {
            \Log::info('getMessages called with roomId: ' . $roomId);
            
            $doctor = Auth::guard('doctor')->user();
            \Log::info('Doctor authenticated: ' . ($doctor ? $doctor->name : 'null'));
            
            $chatRoom = ChatRoom::where('room_id', $roomId)
                ->where('user_type', 'doctor')
                ->where('user_id', $doctor->id)
                ->firstOrFail();
            
            \Log::info('Chat room found: ' . $chatRoom->id . ' - ' . $chatRoom->subject);
            
            $messages = $chatRoom->messages()
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($message) {
                    return [
                        'id' => $message->id,
                        'sender_type' => $message->sender_type,
                        'sender_id' => $message->sender_id,
                        'sender_name' => $message->sender_name,
                        'message' => $message->message,
                        'message_type' => $message->message_type,
                        'is_read' => $message->is_read,
                        'message_status' => $message->message_status,
                        'status_icon' => $message->status_icon,
                        'status_text' => $message->status_text,
                        'created_at' => $message->created_at,
                        'formatted_time' => $message->formatted_time,
                        'time_ago' => $message->time_ago
                    ];
                });
            
            \Log::info('Messages loaded: ' . $messages->count() . ' messages');
            
            // Mark messages as read
            $chatRoom->markAsRead($doctor->id, 'doctor');
            
            // Get typing status
            $typingText = $chatRoom->getTypingIndicatorText($doctor->id, 'doctor');
            
            $response = [
                'success' => true,
                'messages' => $messages,
                'room_status' => $chatRoom->status,
                'admin' => $chatRoom->admin,
                'typing_status' => [
                    'is_typing' => $chatRoom->isAnyoneTyping($doctor->id, 'doctor'),
                    'typing_text' => $typingText,
                    'typing_users' => $chatRoom->getTypingUsers($doctor->id, 'doctor')
                ]
            ];
            
            \Log::info('Response prepared successfully');
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Error in getMessages: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to load messages',
                'message' => $e->getMessage(),
                'messages' => []
            ], 500);
        }
    }
    
    public function closeChat($roomId)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $chatRoom = ChatRoom::where('room_id', $roomId)
                ->where('user_type', 'doctor')
                ->where('user_id', $doctor->id)
                ->firstOrFail();
            
            $chatRoom->close();
            
            // Add system message
            $chatRoom->addMessage(
                'system',
                0,
                'Chat telah ditutup oleh dokter.',
                'system'
            );
            
            return redirect()->route('doctors.dashboard.hubungi-admin')
                ->with('success', 'Chat berhasil ditutup.');
                
        } catch (\Exception $e) {
            \Log::error('Error closing chat: ' . $e->getMessage());
            return redirect()->route('doctors.dashboard.hubungi-admin')
                ->with('error', 'Gagal menutup chat: ' . $e->getMessage());
        }
    }
    
    public function getUnreadCount()
    {
        $doctor = Auth::guard('doctor')->user();
        
        $unreadCount = ChatRoom::where('user_type', 'doctor')
            ->where('user_id', $doctor->id)
            ->where('status', '!=', 'closed')
            ->get()
            ->sum(function($room) use ($doctor) {
                return $room->getUnreadCount($doctor->id, 'doctor');
            });
        
        return response()->json(['unread_count' => $unreadCount]);
    }
    
    public function getAvailableAdmins()
    {
        try {
            $availableAdmins = AdminAssignment::available()
                ->with('admin')
                ->get()
                ->map(function($assignment) {
                    return [
                        'id' => $assignment->admin_id,
                        'name' => $assignment->admin->name ?? 'Unknown Admin',
                        'current_chats' => $assignment->current_chats,
                        'max_chats' => $assignment->max_concurrent_chats,
                        'workload_percentage' => $assignment->getWorkloadPercentage(),
                        'specializations' => $assignment->specializations ?? []
                    ];
                });
            
            return response()->json([
                'success' => true,
                'admins' => $availableAdmins
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getAvailableAdmins: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to load admin data',
                'message' => $e->getMessage(),
                'admins' => []
            ], 500);
        }
    }

    // Typing indicator methods
    public function startTyping(Request $request, $roomId)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $chatRoom = ChatRoom::where('room_id', $roomId)
                ->where('user_type', 'doctor')
                ->where('user_id', $doctor->id)
                ->firstOrFail();
            
            $chatRoom->startTyping($doctor->id, 'doctor');
            
            return response()->json([
                'success' => true,
                'message' => 'Typing started'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function stopTyping(Request $request, $roomId)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $chatRoom = ChatRoom::where('room_id', $roomId)
                ->where('user_type', 'doctor')
                ->where('user_id', $doctor->id)
                ->firstOrFail();
            
            $chatRoom->stopTyping($doctor->id, 'doctor');
            
            return response()->json([
                'success' => true,
                'message' => 'Typing stopped'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTypingStatus(Request $request, $roomId)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $chatRoom = ChatRoom::where('room_id', $roomId)
                ->where('user_type', 'doctor')
                ->where('user_id', $doctor->id)
                ->firstOrFail();
            
            $typingText = $chatRoom->getTypingIndicatorText($doctor->id, 'doctor');
            
            return response()->json([
                'success' => true,
                'is_typing' => $chatRoom->isAnyoneTyping($doctor->id, 'doctor'),
                'typing_text' => $typingText,
                'typing_users' => $chatRoom->getTypingUsers($doctor->id, 'doctor')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markMessageDelivered(Request $request, $messageId)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $message = ChatMessage::where('id', $messageId)
                ->whereHas('chatRoom', function($query) use ($doctor) {
                    $query->where('user_type', 'doctor')
                          ->where('user_id', $doctor->id);
                })
                ->firstOrFail();
            
            $message->markAsDelivered();
            
            return response()->json([
                'success' => true,
                'message' => 'Message marked as delivered'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markMessageRead(Request $request, $messageId)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            
            $message = ChatMessage::where('id', $messageId)
                ->whereHas('chatRoom', function($query) use ($doctor) {
                    $query->where('user_type', 'doctor')
                          ->where('user_id', $doctor->id);
                })
                ->firstOrFail();
            
            $message->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function autoEscalateStaleChats()
    {
        $staleChats = ChatRoom::where('status', 'waiting')
            ->where('created_at', '<=', now()->subHours(4))
            ->whereNull('admin_id')
            ->get();

        foreach ($staleChats as $chat) {
            if ($chat->canBeEscalated()) {
                $chat->escalate();
                \Log::info("Auto-escalated chat room: {$chat->room_id}");
            }
        }
    }

    private function getChatStatistics($doctorId)
    {
        $totalChats = ChatRoom::where('user_type', 'doctor')
            ->where('user_id', $doctorId)
            ->count();

        $activeChats = ChatRoom::where('user_type', 'doctor')
            ->where('user_id', $doctorId)
            ->whereIn('status', ['waiting', 'active'])
            ->count();

        $closedChats = ChatRoom::where('user_type', 'doctor')
            ->where('user_id', $doctorId)
            ->where('status', 'closed')
            ->count();

        $urgentChats = ChatRoom::where('user_type', 'doctor')
            ->where('user_id', $doctorId)
            ->where('priority', 'urgent')
            ->whereIn('status', ['waiting', 'active'])
            ->count();

        $averageResponseTime = ChatRoom::where('user_type', 'doctor')
            ->where('user_id', $doctorId)
            ->whereNotNull('admin_id')
            ->get()
            ->avg('average_response_time');

        return [
            'total_chats' => $totalChats,
            'active_chats' => $activeChats,
            'closed_chats' => $closedChats,
            'urgent_chats' => $urgentChats,
            'average_response_time' => round($averageResponseTime ?? 0, 1)
        ];
    }
}
