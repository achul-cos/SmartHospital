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
        Schema::table('chat_messages', function (Blueprint $table) {
            // Drop the existing enum column
            $table->dropColumn('sender_type');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            // Recreate the enum column with 'system' added
            $table->enum('sender_type', ['admin', 'doctor', 'patient', 'system'])->after('chat_room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // Drop the column with 'system'
            $table->dropColumn('sender_type');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            // Recreate the original enum column without 'system'
            $table->enum('sender_type', ['admin', 'doctor', 'patient'])->after('chat_room_id');
        });
    }
};
