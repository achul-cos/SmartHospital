<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
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

        $medicalRecords = [
            [
                'chief_complaint' => 'Sakit kepala dan demam selama 3 hari',
                'physical_examination' => 'Tekanan darah normal, suhu 38.5°C, denyut nadi 88 bpm',
                'diagnosis' => 'Demam berdarah dengue (DBD)',
                'treatment_plan' => 'Istirahat total, minum air putih yang banyak, paracetamol 3x1 tablet',
                'notes' => 'Pasien perlu kontrol ulang dalam 3 hari',
                'blood_pressure' => '120/80 mmHg',
                'temperature' => '38.5°C',
                'pulse_rate' => '88 bpm',
                'weight' => '65 kg',
                'height' => '170 cm',
            ],
            [
                'chief_complaint' => 'Batuk dan pilek selama 1 minggu',
                'physical_examination' => 'Tekanan darah normal, suhu 37.2°C, denyut nadi 76 bpm',
                'diagnosis' => 'Infeksi saluran napas atas (ISPA)',
                'treatment_plan' => 'Antibiotik amoxicillin 3x1 kapsul, vitamin C',
                'notes' => 'Hindari makanan pedas dan dingin',
                'blood_pressure' => '110/70 mmHg',
                'temperature' => '37.2°C',
                'pulse_rate' => '76 bpm',
                'weight' => '58 kg',
                'height' => '165 cm',
            ],
            [
                'chief_complaint' => 'Nyeri ulu hati dan mual',
                'physical_examination' => 'Tekanan darah normal, suhu 36.8°C, denyut nadi 72 bpm',
                'diagnosis' => 'Gastritis (maag)',
                'treatment_plan' => 'Antasida 3x1 tablet, ranitidin 2x1 tablet',
                'notes' => 'Makan teratur, hindari makanan asam dan pedas',
                'blood_pressure' => '115/75 mmHg',
                'temperature' => '36.8°C',
                'pulse_rate' => '72 bpm',
                'weight' => '70 kg',
                'height' => '175 cm',
            ],
            [
                'chief_complaint' => 'Sesak napas dan batuk berdahak',
                'physical_examination' => 'Tekanan darah 140/90 mmHg, suhu 37.5°C, denyut nadi 92 bpm',
                'diagnosis' => 'Bronkitis akut',
                'treatment_plan' => 'Antibiotik, ekspektoran, bronkodilator',
                'notes' => 'Istirahat yang cukup, hindari rokok',
                'blood_pressure' => '140/90 mmHg',
                'temperature' => '37.5°C',
                'pulse_rate' => '92 bpm',
                'weight' => '75 kg',
                'height' => '180 cm',
            ],
            [
                'chief_complaint' => 'Nyeri sendi lutut kanan',
                'physical_examination' => 'Tekanan darah normal, suhu 36.9°C, denyut nadi 78 bpm',
                'diagnosis' => 'Osteoarthritis lutut',
                'treatment_plan' => 'Analgesik, fisioterapi, kompres hangat',
                'notes' => 'Hindari aktivitas berat, gunakan tongkat jika perlu',
                'blood_pressure' => '125/85 mmHg',
                'temperature' => '36.9°C',
                'pulse_rate' => '78 bpm',
                'weight' => '80 kg',
                'height' => '175 cm',
            ],
        ];

        foreach ($medicalRecords as $index => $record) {
            $patient = $patients->random();
            $doctor = $doctors->random();
            
            // Get a random appointment for this patient and doctor
            $appointment = Appointment::where('patient_id', $patient->id)
                ->where('doctor_id', $doctor->id)
                ->inRandomOrder()
                ->first();

            MedicalRecord::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'appointment_id' => $appointment ? $appointment->id : null,
                'record_date' => Carbon::now()->subDays(rand(1, 30)),
                'chief_complaint' => $record['chief_complaint'],
                'physical_examination' => $record['physical_examination'],
                'diagnosis' => $record['diagnosis'],
                'treatment_plan' => $record['treatment_plan'],
                'notes' => $record['notes'],
                'blood_pressure' => $record['blood_pressure'],
                'temperature' => $record['temperature'],
                'pulse_rate' => $record['pulse_rate'],
                'weight' => $record['weight'],
                'height' => $record['height'],
            ]);
        }
    }
}
