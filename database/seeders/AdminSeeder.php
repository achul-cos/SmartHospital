<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Administrator',
            'employee_number' => 'ADM001',
            'email' => 'admin@smarthospital.com',
            'phone_number' => '081234567890',
            'password' => Hash::make('password'),
            'position' => 'Super Admin',
        ]);

        Admin::create([
            'name' => 'Manager Rumah Sakit',
            'employee_number' => 'ADM002',
            'email' => 'manager@smarthospital.com',
            'phone_number' => '081234567891',
            'password' => Hash::make('password'),
            'position' => 'Hospital Manager',
        ]);
    }
}
