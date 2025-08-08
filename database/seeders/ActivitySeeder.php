<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = Admin::all();
        $doctors = Doctor::all();
        $patients = Patient::all();

        if ($admins->isEmpty() || $doctors->isEmpty() || $patients->isEmpty()) {
            $this->command->warn('Admins, Doctors, or Patients not found. Please run AdminSeeder, DoctorSeeder, and PatientSeeder first.');
            return;
        }

        $activities = [];

        // Admin activities
        $adminActivities = [
            [
                'user_type' => 'admin',
                'user_id' => $admins->first()->id,
                'action' => 'login',
                'description' => 'Admin berhasil login ke sistem',
                'metadata' => ['ip' => '192.168.1.100', 'user_agent' => 'Chrome/91.0'],
            ],
            [
                'user_type' => 'admin',
                'user_id' => $admins->first()->id,
                'action' => 'view_dashboard',
                'description' => 'Admin melihat dashboard utama',
                'metadata' => ['page' => 'dashboard', 'section' => 'overview'],
            ],
            [
                'user_type' => 'admin',
                'user_id' => $admins->first()->id,
                'action' => 'view_reports',
                'description' => 'Admin melihat laporan keuangan',
                'metadata' => ['report_type' => 'financial', 'period' => 'daily'],
            ],
        ];

        // Doctor activities
        $doctorActivities = [
            [
                'user_type' => 'doctor',
                'user_id' => $doctors->first()->id,
                'action' => 'login',
                'description' => 'Dokter berhasil login ke sistem',
                'metadata' => ['ip' => '192.168.1.101', 'user_agent' => 'Firefox/89.0'],
            ],
            [
                'user_type' => 'doctor',
                'user_id' => $doctors->first()->id,
                'action' => 'check_in',
                'description' => 'Dokter melakukan check-in',
                'metadata' => ['time' => '07:30:00', 'location' => 'main_entrance'],
            ],
            [
                'user_type' => 'doctor',
                'user_id' => $doctors->skip(1)->first()->id,
                'action' => 'login',
                'description' => 'Dokter berhasil login ke sistem',
                'metadata' => ['ip' => '192.168.1.102', 'user_agent' => 'Safari/14.0'],
            ],
            [
                'user_type' => 'doctor',
                'user_id' => $doctors->skip(1)->first()->id,
                'action' => 'check_in',
                'description' => 'Dokter melakukan check-in (terlambat)',
                'metadata' => ['time' => '08:15:00', 'location' => 'main_entrance', 'status' => 'late'],
            ],
            [
                'user_type' => 'doctor',
                'user_id' => $doctors->skip(2)->first()->id,
                'action' => 'login',
                'description' => 'Dokter berhasil login ke sistem',
                'metadata' => ['ip' => '192.168.1.103', 'user_agent' => 'Edge/91.0'],
            ],
            [
                'user_type' => 'doctor',
                'user_id' => $doctors->skip(2)->first()->id,
                'action' => 'check_in',
                'description' => 'Dokter melakukan check-in',
                'metadata' => ['time' => '07:45:00', 'location' => 'main_entrance'],
            ],
        ];

        // Patient activities
        $patientActivities = [
            [
                'user_type' => 'patient',
                'user_id' => $patients->first()->id,
                'action' => 'login',
                'description' => 'Pasien berhasil login ke sistem',
                'metadata' => ['ip' => '192.168.1.104', 'user_agent' => 'Chrome/91.0'],
            ],
            [
                'user_type' => 'patient',
                'user_id' => $patients->first()->id,
                'action' => 'view_medical_record',
                'description' => 'Pasien melihat rekam medis',
                'metadata' => ['record_id' => 1, 'access_type' => 'view'],
            ],
            [
                'user_type' => 'patient',
                'user_id' => $patients->skip(1)->first()->id,
                'action' => 'login',
                'description' => 'Pasien berhasil login ke sistem',
                'metadata' => ['ip' => '192.168.1.105', 'user_agent' => 'Mobile Safari'],
            ],
            [
                'user_type' => 'patient',
                'user_id' => $patients->skip(1)->first()->id,
                'action' => 'book_appointment',
                'description' => 'Pasien membuat janji temu baru',
                'metadata' => ['appointment_id' => 1, 'doctor_id' => $doctors->first()->id],
            ],
        ];

        // Combine all activities
        $allActivities = array_merge($adminActivities, $doctorActivities, $patientActivities);

        // Create activities with timestamps
        foreach ($allActivities as $activityData) {
            $activity = Activity::create($activityData);
            
            // Set random timestamp within last 24 hours
            $activity->created_at = now()->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $activity->updated_at = $activity->created_at;
            $activity->save();
        }

        // Add some historical activities (last 7 days)
        for ($i = 1; $i <= 7; $i++) {
            $date = now()->subDays($i);
            
            // Random admin activities
            foreach ($admins as $admin) {
                $actions = ['login', 'view_dashboard', 'view_reports', 'manage_doctors', 'manage_patients'];
                $action = $actions[array_rand($actions)];
                
                Activity::create([
                    'user_type' => 'admin',
                    'user_id' => $admin->id,
                    'action' => $action,
                    'description' => $this->getActivityDescription('admin', $action),
                    'metadata' => ['ip' => '192.168.1.' . rand(100, 199)],
                    'created_at' => $date->copy()->addHours(rand(8, 17))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(8, 17))->addMinutes(rand(0, 59)),
                ]);
            }

            // Random doctor activities
            foreach ($doctors as $doctor) {
                $actions = ['login', 'check_in', 'check_out', 'view_patients', 'update_medical_record'];
                $action = $actions[array_rand($actions)];
                
                Activity::create([
                    'user_type' => 'doctor',
                    'user_id' => $doctor->id,
                    'action' => $action,
                    'description' => $this->getActivityDescription('doctor', $action),
                    'metadata' => ['ip' => '192.168.1.' . rand(200, 299)],
                    'created_at' => $date->copy()->addHours(rand(7, 18))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(7, 18))->addMinutes(rand(0, 59)),
                ]);
            }

            // Random patient activities
            foreach ($patients->take(3) as $patient) {
                $actions = ['login', 'view_medical_record', 'book_appointment', 'view_prescription'];
                $action = $actions[array_rand($actions)];
                
                Activity::create([
                    'user_type' => 'patient',
                    'user_id' => $patient->id,
                    'action' => $action,
                    'description' => $this->getActivityDescription('patient', $action),
                    'metadata' => ['ip' => '192.168.1.' . rand(300, 399)],
                    'created_at' => $date->copy()->addHours(rand(9, 20))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(9, 20))->addMinutes(rand(0, 59)),
                ]);
            }
        }

        $this->command->info('Activity logs seeded successfully!');
    }

    private function getActivityDescription($userType, $action)
    {
        $descriptions = [
            'admin' => [
                'login' => 'Admin berhasil login ke sistem',
                'view_dashboard' => 'Admin melihat dashboard utama',
                'view_reports' => 'Admin melihat laporan sistem',
                'manage_doctors' => 'Admin mengelola data dokter',
                'manage_patients' => 'Admin mengelola data pasien',
            ],
            'doctor' => [
                'login' => 'Dokter berhasil login ke sistem',
                'check_in' => 'Dokter melakukan check-in',
                'check_out' => 'Dokter melakukan check-out',
                'view_patients' => 'Dokter melihat daftar pasien',
                'update_medical_record' => 'Dokter memperbarui rekam medis',
            ],
            'patient' => [
                'login' => 'Pasien berhasil login ke sistem',
                'view_medical_record' => 'Pasien melihat rekam medis',
                'book_appointment' => 'Pasien membuat janji temu',
                'view_prescription' => 'Pasien melihat resep obat',
            ],
        ];

        return $descriptions[$userType][$action] ?? 'Aktivitas sistem';
    }
}
