<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call custom seeders in order
        $this->call([
            AdminSeeder::class,
            DoctorSeeder::class,
            DoctorScheduleSeeder::class,
            PatientSeeder::class,
            AppointmentSeeder::class,
            DoctorAttendanceSeeder::class,
            RevenueSeeder::class,
            ActivitySeeder::class,
            MedicalRecordSeeder::class,
            PrescriptionSeeder::class,
            AdminAssignmentSeeder::class,
        ]);
    }
}
