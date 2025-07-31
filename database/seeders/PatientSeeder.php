<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'name' => 'John Doe',
            'card_number' => 'PAT001',
            'gender' => 'male',
            'birth_date' => '1990-05-15',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'phone_number' => '081234567895',
            'email' => 'john.doe@email.com',
            'notes' => 'Pasien dengan riwayat alergi terhadap obat tertentu',
        ]);

        Patient::create([
            'name' => 'Jane Smith',
            'card_number' => 'PAT002',
            'gender' => 'female',
            'birth_date' => '1985-08-22',
            'address' => 'Jl. Thamrin No. 456, Jakarta Pusat',
            'phone_number' => '081234567896',
            'email' => 'jane.smith@email.com',
            'notes' => 'Pasien dengan tekanan darah tinggi',
            'password' => Hash::make('password'),
        ]);

        Patient::create([
            'name' => 'Ahmad Rahman',
            'card_number' => 'PAT003',
            'gender' => 'male',
            'birth_date' => '1995-12-10',
            'address' => 'Jl. Gatot Subroto No. 789, Jakarta Selatan',
            'phone_number' => '081234567897',
            'email' => 'ahmad.rahman@email.com',
            'notes' => 'Pasien dengan diabetes tipe 2',
            'password' => Hash::make('password'),
        ]);

        Patient::create([
            'name' => 'Achul',
            'card_number' => 'PAT004',
            'gender' => 'male',
            'birth_date' => '2005-11-10',
            'address' => 'Jl. Putri Hijau No 1',
            'phone_number' => '089668914466',
            'email' => 'arulnasrullah2468@gmail.com',
            'notes' => 'Remaja Jompo',
            'password' => Hash::make('password'),
        ]);        
    }
}
