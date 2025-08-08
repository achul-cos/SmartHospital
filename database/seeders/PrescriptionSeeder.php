<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use Carbon\Carbon;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        if ($patients->count() === 0 || $doctors->count() === 0) {
            return;
        }

        $prescriptions = [
            [
                'medications' => 'Paracetamol 500mg tablet',
                'dosage_instructions' => '3x1 tablet sehari setelah makan',
                'special_instructions' => 'Minum dengan air putih yang banyak',
                'notes' => 'Untuk mengatasi demam dan sakit kepala',
                'status' => 'active',
            ],
            [
                'medications' => 'Amoxicillin 500mg kapsul',
                'dosage_instructions' => '3x1 kapsul sehari sebelum makan',
                'special_instructions' => 'Habiskan semua obat sesuai dosis',
                'notes' => 'Antibiotik untuk infeksi saluran napas',
                'status' => 'active',
            ],
            [
                'medications' => 'Antasida tablet',
                'dosage_instructions' => '3x1 tablet sehari setelah makan',
                'special_instructions' => 'Kunyah tablet sebelum ditelan',
                'notes' => 'Untuk mengatasi nyeri ulu hati',
                'status' => 'completed',
            ],
            [
                'medications' => 'Ranitidin 150mg tablet',
                'dosage_instructions' => '2x1 tablet sehari sebelum makan',
                'special_instructions' => 'Minum 30 menit sebelum makan',
                'notes' => 'Untuk mengurangi asam lambung',
                'status' => 'active',
            ],
            [
                'medications' => 'Vitamin C 1000mg tablet',
                'dosage_instructions' => '1x1 tablet sehari setelah makan',
                'special_instructions' => 'Minum dengan air putih',
                'notes' => 'Untuk meningkatkan daya tahan tubuh',
                'status' => 'active',
            ],
            [
                'medications' => 'Ibuprofen 400mg tablet',
                'dosage_instructions' => '3x1 tablet sehari setelah makan',
                'special_instructions' => 'Jangan minum dalam keadaan perut kosong',
                'notes' => 'Untuk mengatasi nyeri dan peradangan',
                'status' => 'completed',
            ],
            [
                'medications' => 'Salbutamol inhaler',
                'dosage_instructions' => '2x2 hisapan sehari atau saat sesak',
                'special_instructions' => 'Kocok inhaler sebelum digunakan',
                'notes' => 'Untuk mengatasi sesak napas',
                'status' => 'active',
            ],
            [
                'medications' => 'Omeprazole 20mg kapsul',
                'dosage_instructions' => '1x1 kapsul sehari sebelum sarapan',
                'special_instructions' => 'Jangan dibuka kapsulnya',
                'notes' => 'Untuk mengatasi asam lambung berlebih',
                'status' => 'active',
            ],
        ];

        foreach ($prescriptions as $index => $prescription) {
            $patient = $patients->random();
            $doctor = $doctors->random();
            
            // Get a random medical record for this patient and doctor
            $medicalRecord = MedicalRecord::where('patient_id', $patient->id)
                ->where('doctor_id', $doctor->id)
                ->inRandomOrder()
                ->first();

            // Get a random appointment for this patient and doctor
            $appointment = Appointment::where('patient_id', $patient->id)
                ->where('doctor_id', $doctor->id)
                ->inRandomOrder()
                ->first();

            Prescription::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'medical_record_id' => $medicalRecord ? $medicalRecord->id : null,
                'appointment_id' => $appointment ? $appointment->id : null,
                'prescription_date' => Carbon::now()->subDays(rand(1, 30)),
                'medications' => $prescription['medications'],
                'dosage_instructions' => $prescription['dosage_instructions'],
                'special_instructions' => $prescription['special_instructions'],
                'notes' => $prescription['notes'],
                'status' => $prescription['status'],
            ]);
        }
    }
}
