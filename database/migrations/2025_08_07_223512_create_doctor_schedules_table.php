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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->enum('status', ['kerja', 'libur', 'izin'])->default('kerja');
            $table->time('start_time')->nullable(); // Jam mulai kerja
            $table->time('end_time')->nullable();   // Jam selesai kerja
            $table->text('notes')->nullable();      // Catatan khusus (misal: alasan izin)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure one schedule per doctor per day
            $table->unique(['doctor_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
