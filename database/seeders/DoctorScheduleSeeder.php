<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all doctors
        $doctors = Doctor::all();

        if ($doctors->isEmpty()) {
            $this->command->info('No doctors found. Please run DoctorSeeder first.');
            return;
        }

        foreach ($doctors as $doctor) {
            // Create schedules for each doctor
            $this->createDoctorSchedules($doctor);
        }

        $this->command->info('Doctor schedules seeded successfully!');
    }

    private function createDoctorSchedules(Doctor $doctor)
    {
        // Create different schedules based on doctor specialization or ID
        $doctorIndex = $doctor->id % 4; // 4 different schedule patterns
        
        switch ($doctorIndex) {
            case 0:
                $schedules = $this->getMorningShiftSchedule($doctor);
                break;
            case 1:
                $schedules = $this->getAfternoonShiftSchedule($doctor);
                break;
            case 2:
                $schedules = $this->getFlexibleSchedule($doctor);
                break;
            case 3:
                $schedules = $this->getPartTimeSchedule($doctor);
                break;
            default:
                $schedules = $this->getMorningShiftSchedule($doctor);
        }

        // Create schedules for this doctor
        foreach ($schedules as $schedule) {
            DoctorSchedule::updateOrCreate(
                [
                    'doctor_id' => $schedule['doctor_id'],
                    'day_of_week' => $schedule['day_of_week']
                ],
                $schedule
            );
        }

        $this->command->info("Schedules created for doctor: {$doctor->name} (Pattern: {$doctorIndex})");
    }

    private function getMorningShiftSchedule(Doctor $doctor)
    {
        return [
            // Senin - Kerja Pagi
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'monday',
                'status' => 'kerja',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'notes' => 'Shift pagi',
                'is_active' => true,
            ],
            // Selasa - Kerja Pagi
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'tuesday',
                'status' => 'kerja',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'notes' => 'Shift pagi',
                'is_active' => true,
            ],
            // Rabu - Kerja Pagi
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'wednesday',
                'status' => 'kerja',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'notes' => 'Shift pagi',
                'is_active' => true,
            ],
            // Kamis - Kerja Pagi
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'thursday',
                'status' => 'kerja',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'notes' => 'Shift pagi',
                'is_active' => true,
            ],
            // Jumat - Kerja Pagi
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'friday',
                'status' => 'kerja',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'notes' => 'Shift pagi',
                'is_active' => true,
            ],
            // Sabtu - Libur
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'saturday',
                'status' => 'libur',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Hari libur',
                'is_active' => true,
            ],
            // Minggu - Libur
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'sunday',
                'status' => 'libur',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Hari libur',
                'is_active' => true,
            ],
        ];
    }

    private function getAfternoonShiftSchedule(Doctor $doctor)
    {
        return [
            // Senin - Kerja Siang
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'monday',
                'status' => 'kerja',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'notes' => 'Shift siang',
                'is_active' => true,
            ],
            // Selasa - Kerja Siang
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'tuesday',
                'status' => 'kerja',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'notes' => 'Shift siang',
                'is_active' => true,
            ],
            // Rabu - Kerja Siang
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'wednesday',
                'status' => 'kerja',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'notes' => 'Shift siang',
                'is_active' => true,
            ],
            // Kamis - Kerja Siang
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'thursday',
                'status' => 'kerja',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'notes' => 'Shift siang',
                'is_active' => true,
            ],
            // Jumat - Kerja Siang
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'friday',
                'status' => 'kerja',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'notes' => 'Shift siang',
                'is_active' => true,
            ],
            // Sabtu - Kerja Siang
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'saturday',
                'status' => 'kerja',
                'start_time' => '13:00:00',
                'end_time' => '21:00:00',
                'notes' => 'Shift siang',
                'is_active' => true,
            ],
            // Minggu - Libur
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'sunday',
                'status' => 'libur',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Hari libur',
                'is_active' => true,
            ],
        ];
    }

    private function getFlexibleSchedule(Doctor $doctor)
    {
        return [
            // Senin - Kerja
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'monday',
                'status' => 'kerja',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'Jadwal fleksibel',
                'is_active' => true,
            ],
            // Selasa - Kerja
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'tuesday',
                'status' => 'kerja',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'Jadwal fleksibel',
                'is_active' => true,
            ],
            // Rabu - Izin
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'wednesday',
                'status' => 'izin',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Izin keluarga',
                'is_active' => true,
            ],
            // Kamis - Kerja
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'thursday',
                'status' => 'kerja',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'Jadwal fleksibel',
                'is_active' => true,
            ],
            // Jumat - Kerja
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'friday',
                'status' => 'kerja',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'Jadwal fleksibel',
                'is_active' => true,
            ],
            // Sabtu - Kerja Setengah Hari
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'saturday',
                'status' => 'kerja',
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'notes' => 'Praktik setengah hari',
                'is_active' => true,
            ],
            // Minggu - Libur
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'sunday',
                'status' => 'libur',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Hari libur',
                'is_active' => true,
            ],
        ];
    }

    private function getPartTimeSchedule(Doctor $doctor)
    {
        return [
            // Senin - Kerja
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'monday',
                'status' => 'kerja',
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'notes' => 'Praktik paruh waktu',
                'is_active' => true,
            ],
            // Selasa - Libur
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'tuesday',
                'status' => 'libur',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Hari libur',
                'is_active' => true,
            ],
            // Rabu - Kerja
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'wednesday',
                'status' => 'kerja',
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'notes' => 'Praktik paruh waktu',
                'is_active' => true,
            ],
            // Kamis - Libur
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'thursday',
                'status' => 'libur',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Hari libur',
                'is_active' => true,
            ],
            // Jumat - Kerja
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'friday',
                'status' => 'kerja',
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'notes' => 'Praktik paruh waktu',
                'is_active' => true,
            ],
            // Sabtu - Izin
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'saturday',
                'status' => 'izin',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Izin pribadi',
                'is_active' => true,
            ],
            // Minggu - Libur
            [
                'doctor_id' => $doctor->id,
                'day_of_week' => 'sunday',
                'status' => 'libur',
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Hari libur',
                'is_active' => true,
            ],
        ];
    }
}
