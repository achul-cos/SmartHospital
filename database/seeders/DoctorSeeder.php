<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::create([
            'name' => 'Dr. Sarah Johnson',
            'doctor_number' => 'DOC001',
            'email' => 'sarah.johnson@smarthospital.com',
            'phone' => '+62 812 3456 7890',
            'specialization' => 'Kardiologi',
            'license_number' => 'SIP.123456.2020',
            'experience_years' => 8,
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'password' => Hash::make('password123'),
            'gender' => 'female',
            'status' => 'active',
            'email_notifications' => true,
            'sms_notifications' => true,
            'chat_notifications' => true,
            'appointment_notifications' => true,
        ]);

        Doctor::create([
            'name' => 'Dr. Michael Chen',
            'doctor_number' => 'DOC002',
            'email' => 'michael.chen@smarthospital.com',
            'phone' => '+62 812 3456 7891',
            'specialization' => 'Neurologi',
            'license_number' => 'SIP.123457.2019',
            'experience_years' => 12,
            'address' => 'Jl. Thamrin No. 456, Jakarta Pusat',
            'password' => Hash::make('password123'),
            'gender' => 'male',
            'status' => 'active',
            'email_notifications' => true,
            'sms_notifications' => false,
            'chat_notifications' => true,
            'appointment_notifications' => true,
        ]);

        Doctor::create([
            'name' => 'Dr. Emily Rodriguez',
            'doctor_number' => 'DOC003',
            'email' => 'emily.rodriguez@smarthospital.com',
            'phone' => '+62 812 3456 7892',
            'specialization' => 'Pediatri',
            'license_number' => 'SIP.123458.2021',
            'experience_years' => 6,
            'address' => 'Jl. Gatot Subroto No. 789, Jakarta Selatan',
            'password' => Hash::make('password123'),
            'gender' => 'female',
            'status' => 'active',
            'email_notifications' => true,
            'sms_notifications' => true,
            'chat_notifications' => false,
            'appointment_notifications' => true,
        ]);

        Doctor::create([
            'name' => 'Dr. David Kim',
            'doctor_number' => 'DOC004',
            'email' => 'david.kim@smarthospital.com',
            'phone' => '+62 812 3456 7893',
            'specialization' => 'Ortopedi',
            'license_number' => 'SIP.123459.2018',
            'experience_years' => 15,
            'address' => 'Jl. Rasuna Said No. 321, Jakarta Selatan',
            'password' => Hash::make('password123'),
            'gender' => 'male',
            'status' => 'active',
            'email_notifications' => false,
            'sms_notifications' => true,
            'chat_notifications' => true,
            'appointment_notifications' => true,
        ]);

        Doctor::create([
            'name' => 'Dr. Lisa Wang',
            'doctor_number' => 'DOC005',
            'email' => 'lisa.wang@smarthospital.com',
            'phone' => '+62 812 3456 7894',
            'specialization' => 'Dermatologi',
            'license_number' => 'SIP.123460.2022',
            'experience_years' => 4,
            'address' => 'Jl. Kuningan No. 654, Jakarta Selatan',
            'password' => Hash::make('password123'),
            'gender' => 'female',
            'status' => 'active',
            'email_notifications' => true,
            'sms_notifications' => true,
            'chat_notifications' => true,
            'appointment_notifications' => false,
        ]);
    }
}
