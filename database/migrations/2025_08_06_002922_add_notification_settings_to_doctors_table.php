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
        Schema::table('doctors', function (Blueprint $table) {
            // Add notification settings after updated_at
            $table->boolean('email_notifications')->default(true)->after('updated_at');
            $table->boolean('sms_notifications')->default(true)->after('email_notifications');
            $table->boolean('chat_notifications')->default(true)->after('sms_notifications');
            $table->boolean('appointment_notifications')->default(true)->after('chat_notifications');
            
            // Add account status fields
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active')->after('appointment_notifications');
            $table->timestamp('deactivated_at')->nullable()->after('status');
            $table->softDeletes()->after('deactivated_at');
            
            // Add missing fields for profile
            $table->string('license_number')->nullable()->after('specialization');
            $table->integer('experience_years')->nullable()->after('license_number');
            $table->text('address')->nullable()->after('experience_years');
            
            // Rename phone_number to phone for consistency
            $table->renameColumn('phone_number', 'phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications',
                'sms_notifications', 
                'chat_notifications',
                'appointment_notifications',
                'status',
                'deactivated_at',
                'license_number',
                'experience_years',
                'address'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
