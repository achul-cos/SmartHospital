<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            $this->command->warn('Doctors or Patients not found. Please run DoctorSeeder and PatientSeeder first.');
            return;
        }

        // Today's appointments
        $today = now();
        $appointments = [
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $today->copy()->setTime(9, 0),
                'status' => 'completed',
                'notes' => 'Kontrol rutin pasca operasi',
                'fee' => 150000,
            ],
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $today->copy()->setTime(10, 30),
                'status' => 'in_progress',
                'notes' => 'Pemeriksaan umum',
                'fee' => 100000,
            ],
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $today->copy()->setTime(14, 0),
                'status' => 'scheduled',
                'notes' => 'Konsultasi jantung',
                'fee' => 200000,
            ],
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $today->copy()->setTime(15, 30),
                'status' => 'scheduled',
                'notes' => 'Pemeriksaan neurologi',
                'fee' => 250000,
            ],
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $today->copy()->setTime(16, 0),
                'status' => 'scheduled',
                'notes' => 'Kontrol anak',
                'fee' => 120000,
            ],
        ];

        // Yesterday's appointments
        $yesterday = now()->subDay();
        $yesterdayAppointments = [
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $yesterday->copy()->setTime(8, 30),
                'status' => 'completed',
                'notes' => 'Pemeriksaan darah',
                'fee' => 80000,
            ],
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $yesterday->copy()->setTime(11, 0),
                'status' => 'completed',
                'notes' => 'Konsultasi jantung',
                'fee' => 200000,
            ],
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $yesterday->copy()->setTime(13, 30),
                'status' => 'completed',
                'notes' => 'Pemeriksaan mata',
                'fee' => 150000,
            ],
        ];

        // This week's appointments
        $thisWeek = now()->addDays(rand(1, 6));
        $thisWeekAppointments = [
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $thisWeek->copy()->setTime(9, 0),
                'status' => 'scheduled',
                'notes' => 'Kontrol rutin',
                'fee' => 100000,
            ],
            [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => $thisWeek->copy()->setTime(14, 0),
                'status' => 'scheduled',
                'notes' => 'Pemeriksaan gigi',
                'fee' => 120000,
            ],
        ];

        // Combine all appointments
        $allAppointments = array_merge($appointments, $yesterdayAppointments, $thisWeekAppointments);

        foreach ($allAppointments as $appointmentData) {
            Appointment::create($appointmentData);
        }

        $this->command->info('Appointments seeded successfully!');
    }
}
