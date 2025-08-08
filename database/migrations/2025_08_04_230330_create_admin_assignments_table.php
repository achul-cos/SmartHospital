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
        Schema::create('admin_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->enum('status', ['online', 'busy', 'offline'])->default('offline');
            $table->integer('max_concurrent_chats')->default(5); // Max chats per admin
            $table->integer('current_chats')->default(0); // Current active chats
            $table->json('specializations')->nullable(); // Admin specializations
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['admin_id', 'status']);
            $table->index('status');
            $table->index('current_chats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_assignments');
    }
};
