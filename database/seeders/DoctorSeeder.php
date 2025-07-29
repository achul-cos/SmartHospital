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
            'phone_number' => '081234567892',
            'specialization' => 'Cardiologist',
            'password' => Hash::make('password'),
            'gender' => 'female',
        ]);

        Doctor::create([
            'name' => 'Dr. Michael Chen',
            'doctor_number' => 'DOC002',
            'email' => 'michael.chen@smarthospital.com',
            'phone_number' => '081234567893',
            'specialization' => 'Neurologist',
            'password' => Hash::make('password'),
            'gender' => 'male',
        ]);

        Doctor::create([
            'name' => 'Dr. Emily Rodriguez',
            'doctor_number' => 'DOC003',
            'email' => 'emily.rodriguez@smarthospital.com',
            'phone_number' => '081234567894',
            'specialization' => 'Pediatrician',
            'password' => Hash::make('password'),
            'gender' => 'female',
        ]);
    }
}
