<?php

namespace App\Http\Controllers\Doctors\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DoctorsDashboardPengaturanAkunController extends Controller
{
    public function index()
    {
        $doctor = Auth::guard('doctor')->user();
        return view('doctors.dashboard.pengaturan-akun', compact('doctor'));
    }

    public function updateProfile(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('doctors')->ignore($doctor->id),
            ],
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:50',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $doctor->update($request->only([
                'name',
                'email',
                'phone',
                'specialization',
                'license_number',
                'experience_years',
                'address'
            ]));

            return redirect()->back()
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function changePassword(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check current password
        if (!Hash::check($request->current_password, $doctor->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak benar'])
                ->withInput();
        }

        try {
            $doctor->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->back()
                ->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateNotifications(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'chat_notifications' => 'boolean',
            'appointment_notifications' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $doctor->update([
                'email_notifications' => $request->has('email_notifications'),
                'sms_notifications' => $request->has('sms_notifications'),
                'chat_notifications' => $request->has('chat_notifications'),
                'appointment_notifications' => $request->has('appointment_notifications'),
            ]);

            return redirect()->back()
                ->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui pengaturan notifikasi: ' . $e->getMessage());
        }
    }

    public function deactivate(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        try {
            $doctor->update([
                'status' => 'inactive',
                'deactivated_at' => now()
            ]);

            Auth::guard('doctor')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('doctors.login')
                ->with('success', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin untuk mengaktifkannya kembali.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menonaktifkan akun: ' . $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        try {
            // Soft delete the doctor
            $doctor->update([
                'deleted_at' => now(),
                'status' => 'deleted'
            ]);

            Auth::guard('doctor')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('doctors.login')
                ->with('success', 'Akun Anda telah dihapus. Terima kasih telah menggunakan layanan kami.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }

    public function exportData(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        try {
            // Get all doctor's data
            $data = [
                'profile' => $doctor->toArray(),
                'appointments' => $doctor->appointments()->with('patient')->get()->toArray(),
                'medical_records' => $doctor->medicalRecords()->with('patient')->get()->toArray(),
                'prescriptions' => $doctor->prescriptions()->with('patient')->get()->toArray(),
                'attendance' => $doctor->attendance()->get()->toArray(),
                'chat_rooms' => $doctor->chatRooms()->with('messages')->get()->toArray(),
            ];

            // Generate JSON file
            $filename = 'doctor_data_' . $doctor->id . '_' . date('Y-m-d_H-i-s') . '.json';
            
            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
