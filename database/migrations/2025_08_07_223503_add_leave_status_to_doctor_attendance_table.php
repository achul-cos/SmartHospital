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
        Schema::table('doctor_attendance', function (Blueprint $table) {
            // Drop the existing enum column
            $table->dropColumn('status');
        });

        Schema::table('doctor_attendance', function (Blueprint $table) {
            // Recreate the enum column with new values
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'izin', 'libur'])->default('absent')->after('check_out_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_attendance', function (Blueprint $table) {
            // Drop the new enum column
            $table->dropColumn('status');
        });

        Schema::table('doctor_attendance', function (Blueprint $table) {
            // Recreate the original enum column
            $table->enum('status', ['present', 'absent', 'late', 'half_day'])->default('absent')->after('check_out_time');
        });
    }
};
