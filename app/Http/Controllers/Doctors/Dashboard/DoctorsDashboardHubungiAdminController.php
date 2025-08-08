<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use Carbon\Carbon;
use App\Models\ChatRoom;
use App\Models\AdminAssignment;

class DoctorsDashboardHubungiAdminController extends Controller
{
    public function index()
    {
        $doctor = Auth::guard('doctor')->user();
        
        // Get recent contacts
        $recentContacts = Activity::where('user_type', 'doctor')
            ->where('user_id', $doctor->id)
            ->where('action', 'contact_admin')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get chat rooms for this doctor
        $chatRooms = ChatRoom::where('user_type', 'doctor')
            ->where('user_id', $doctor->id)
            ->with(['admin', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->orderByRaw('COALESCE(last_message_at, created_at) DESC')
            ->get();
        
        // Get available admins
        $availableAdmins = AdminAssignment::available()
            ->with('admin')
            ->get();
        
        // Admin contact info
        $adminContactInfo = [
            'phone' => '+62 21 1234 5678',
            'email' => 'admin@smarthospital.com',
            'whatsapp' => '+62 812 3456 7890',
            'office_hours' => 'Senin - Jumat: 08:00 - 17:00'
        ];
        
        // Common contact topics
        $commonTopics = [
            'schedule_conflict' => 'Konflik Jadwal Praktik',
            'patient_emergency' => 'Keadaan Darurat Pasien',
            'system_issue' => 'Masalah Sistem/Aplikasi',
            'equipment_request' => 'Permintaan Peralatan',
            'policy_question' => 'Pertanyaan Kebijakan',
            'technical_support' => 'Dukungan Teknis',
            'other' => 'Pertanyaan Lainnya'
        ];
        
        return view('doctors.dashboard.hubungi-admin', compact(
            'recentContacts', 
            'adminContactInfo', 
            'commonTopics',
            'chatRooms',
            'availableAdmins'
        ));
    }
    
    public function sendMessage(Request $request) {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent',
            'contact_method' => 'required|in:email,phone,whatsapp,internal_message'
        ]);
        
        $doctor = Auth::guard('doctor')->user();
        
        // Create activity log
        Activity::create([
            'user_type' => 'doctor',
            'user_id' => $doctor->id,
            'action' => 'contact_admin',
            'description' => "Dokter {$doctor->name} menghubungi admin: {$request->subject}",
            'metadata' => [
                'subject' => $request->subject,
                'message' => $request->message,
                'priority' => $request->priority,
                'contact_method' => $request->contact_method,
                'doctor_name' => $doctor->name,
                'doctor_email' => $doctor->email,
                'timestamp' => now()->toISOString()
            ]
        ]);
        
        // Here you can add logic to actually send the message
        // For example: send email, WhatsApp, or store in database
        
        $priorityText = match($request->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak'
        };
        
        $contactMethodText = match($request->contact_method) {
            'email' => 'Email',
            'phone' => 'Telepon',
            'whatsapp' => 'WhatsApp',
            'internal_message' => 'Pesan Internal'
        };
        
        return redirect()->back()->with('success', 
            "Pesan berhasil dikirim ke admin melalui {$contactMethodText} dengan prioritas {$priorityText}. Admin akan segera menghubungi Anda."
        );
    }
    
    public function getQuickContactInfo() {
        $adminContactInfo = [
            'phone' => '+62 21 1234 5678',
            'email' => 'admin@smarthospital.com',
            'whatsapp' => '+62 812 3456 7890',
            'office_hours' => 'Senin - Jumat: 08:00 - 17:00',
            'emergency_phone' => '+62 21 9999 9999'
        ];
        
        return response()->json($adminContactInfo);
    }
}
