<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorAttendance;
use Illuminate\Database\Seeder;

class DoctorAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();

        if ($doctors->isEmpty()) {
            $this->command->warn('Doctors not found. Please run DoctorSeeder first.');
            return;
        }

        // Generate last month's attendance
        $lastMonthAttendance = $this->generateLastMonthAttendance($doctors);
        
        // Today's attendance
        $today = now();
        $todayAttendance = [
            [
                'doctor_id' => $doctors->first()->id,
                'date' => $today->toDateString(),
                'check_in_time' => $today->copy()->setTime(7, 30),
                'check_out_time' => null,
                'status' => 'present',
                'notes' => 'Hadir tepat waktu',
            ],
            [
                'doctor_id' => $doctors->skip(1)->first()->id,
                'date' => $today->toDateString(),
                'check_in_time' => $today->copy()->setTime(8, 15),
                'check_out_time' => null,
                'status' => 'late',
                'notes' => 'Terlambat 15 menit',
            ],
            [
                'doctor_id' => $doctors->skip(2)->first()->id,
                'date' => $today->toDateString(),
                'check_in_time' => $today->copy()->setTime(7, 45),
                'check_out_time' => null,
                'status' => 'present',
                'notes' => 'Hadir tepat waktu',
            ],
        ];

        // Yesterday's attendance
        $yesterday = now()->subDay();
        $yesterdayAttendance = [
            [
                'doctor_id' => $doctors->first()->id,
                'date' => $yesterday->toDateString(),
                'check_in_time' => $yesterday->copy()->setTime(7, 30),
                'check_out_time' => $yesterday->copy()->setTime(16, 0),
                'status' => 'present',
                'notes' => 'Hadir penuh hari',
            ],
            [
                'doctor_id' => $doctors->skip(1)->first()->id,
                'date' => $yesterday->toDateString(),
                'check_in_time' => $yesterday->copy()->setTime(7, 30),
                'check_out_time' => $yesterday->copy()->setTime(12, 0),
                'status' => 'half_day',
                'notes' => 'Setengah hari',
            ],
            [
                'doctor_id' => $doctors->skip(2)->first()->id,
                'date' => $yesterday->toDateString(),
                'check_in_time' => null,
                'check_out_time' => null,
                'status' => 'absent',
                'notes' => 'Tidak hadir',
            ],
        ];

        // This week's attendance (random days)
        $thisWeekAttendance = [];
        for ($i = 2; $i <= 6; $i++) {
            $date = now()->subDays($i);
            foreach ($doctors as $doctor) {
                $status = ['present', 'absent', 'late', 'half_day'][array_rand(['present', 'absent', 'late', 'half_day'])];
                
                $attendance = [
                    'doctor_id' => $doctor->id,
                    'date' => $date->toDateString(),
                    'status' => $status,
                    'notes' => $this->getAttendanceNote($status),
                ];

                if ($status === 'present' || $status === 'late' || $status === 'half_day') {
                    $attendance['check_in_time'] = $date->copy()->setTime(rand(7, 9), rand(0, 59));
                    if ($status === 'present' || $status === 'half_day') {
                        $attendance['check_out_time'] = $date->copy()->setTime(rand(15, 17), rand(0, 59));
                    }
                }

                $thisWeekAttendance[] = $attendance;
            }
        }

        // Combine all attendance records
        $allAttendance = array_merge($lastMonthAttendance, $todayAttendance, $yesterdayAttendance, $thisWeekAttendance);

        foreach ($allAttendance as $attendanceData) {
            DoctorAttendance::create($attendanceData);
        }

        $this->command->info('Doctor attendance seeded successfully!');
    }

    private function getAttendanceNote($status)
    {
        $notes = [
            'present' => 'Hadir tepat waktu',
            'absent' => 'Tidak hadir',
            'late' => 'Terlambat',
            'half_day' => 'Setengah hari',
            'early' => 'Hadir lebih awal',
            'izin' => 'Izin',
            'libur' => 'Libur',
        ];

        return $notes[$status] ?? 'Catatan kehadiran';
    }

    private function generateLastMonthAttendance($doctors)
    {
        $lastMonthAttendance = [];
        $lastMonth = now()->subMonth();
        $daysInMonth = $lastMonth->daysInMonth;

        $statuses = [
            'present' => 45,    // 45% chance
            'early' => 15,      // 15% chance
            'late' => 10,       // 10% chance
            'absent' => 5,      // 5% chance
            'half_day' => 10,   // 10% chance
            'izin' => 10,       // 10% chance
            'libur' => 5        // 5% chance
        ];

        // Generate attendance for each day of last month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $lastMonth->copy()->setDay($day);
            
            // Skip weekends
            if ($date->isWeekend()) {
                foreach ($doctors as $doctor) {
                    $lastMonthAttendance[] = [
                        'doctor_id' => $doctor->id,
                        'date' => $date->toDateString(),
                        'check_in_time' => null,
                        'check_out_time' => null,
                        'status' => 'libur',
                        'notes' => 'Hari libur mingguan'
                    ];
                }
                continue;
            }

            foreach ($doctors as $doctor) {
                // Generate random status based on probability
                $rand = rand(1, 100);
                $currentSum = 0;
                $selectedStatus = 'present';
                
                foreach ($statuses as $status => $probability) {
                    $currentSum += $probability;
                    if ($rand <= $currentSum) {
                        $selectedStatus = $status;
                        break;
                    }
                }

                $attendance = [
                    'doctor_id' => $doctor->id,
                    'date' => $date->toDateString(),
                    'status' => $selectedStatus,
                    'notes' => $this->getAttendanceNote($selectedStatus)
                ];

                // Add check-in and check-out times based on status
                if (in_array($selectedStatus, ['present', 'early', 'late', 'half_day'])) {
                    // Generate check-in time
                    switch ($selectedStatus) {
                        case 'early':
                            $checkInHour = rand(6, 7);
                            break;
                        case 'late':
                            $checkInHour = rand(8, 9);
                            break;
                        case 'half_day':
                            $checkInHour = 8;
                            break;
                        default:
                            $checkInHour = 7;
                    }
                    
                    $attendance['check_in_time'] = $date->copy()->setTime($checkInHour, rand(0, 59));

                    // Generate check-out time
                    if ($selectedStatus === 'half_day') {
                        $attendance['check_out_time'] = $date->copy()->setTime(12, rand(0, 59));
                    } elseif (in_array($selectedStatus, ['present', 'early'])) {
                        $attendance['check_out_time'] = $date->copy()->setTime(rand(16, 17), rand(0, 59));
                    }
                }

                $lastMonthAttendance[] = $attendance;
            }
        }

        return $lastMonthAttendance;
    }
}
