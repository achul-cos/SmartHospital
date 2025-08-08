<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, we need to modify the enum column to include 'confirmed'
        // MySQL doesn't support adding values to enum directly, so we need to recreate the column
        
        // Get current appointments data
        $appointments = DB::table('appointments')->get();
        
        // Drop the status column
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        // Recreate the status column with the new enum values
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('scheduled')->after('appointment_date');
        });
        
        // Restore the data
        foreach ($appointments as $appointment) {
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update(['status' => $appointment->status]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get current appointments data
        $appointments = DB::table('appointments')->get();
        
        // Drop the status column
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        // Recreate the status column with the original enum values
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled')->after('appointment_date');
        });
        
        // Restore the data, converting 'confirmed' to 'scheduled'
        foreach ($appointments as $appointment) {
            $status = $appointment->status === 'confirmed' ? 'scheduled' : $appointment->status;
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update(['status' => $status]);
        }
    }
};
