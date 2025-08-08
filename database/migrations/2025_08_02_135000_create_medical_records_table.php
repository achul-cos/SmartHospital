<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->date('record_date');
            $table->text('chief_complaint'); // Keluhan utama
            $table->text('physical_examination'); // Pemeriksaan fisik
            $table->text('diagnosis'); // Diagnosis
            $table->text('treatment_plan'); // Rencana pengobatan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->string('blood_pressure')->nullable(); // Tekanan darah
            $table->string('temperature')->nullable(); // Suhu
            $table->string('pulse_rate')->nullable(); // Denyut nadi
            $table->string('weight')->nullable(); // Berat badan
            $table->string('height')->nullable(); // Tinggi badan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
