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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_room_id');
            $table->enum('sender_type', ['admin', 'doctor', 'patient', 'system']);
            $table->unsignedBigInteger('sender_id');
            $table->text('message');
            $table->enum('message_type', ['text', 'image', 'file', 'system'])->default('text');
            $table->string('file_path')->nullable(); // For attachments
            $table->string('file_name')->nullable(); // Original filename
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['sender_type', 'sender_id']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
