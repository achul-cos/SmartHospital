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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_id')->unique(); // Unique room identifier
            $table->enum('user_type', ['doctor', 'patient']); // Type of user
            $table->unsignedBigInteger('user_id'); // ID of doctor or patient
            $table->unsignedBigInteger('admin_id')->nullable(); // Assigned admin
            $table->enum('status', ['waiting', 'active', 'closed'])->default('waiting');
            $table->string('subject')->nullable(); // Chat subject/topic
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_type', 'user_id']);
            $table->index(['admin_id', 'status']);
            $table->index('status');
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
