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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->enum('user_type', ['admin', 'doctor', 'patient']);
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Index for better performance
            $table->index(['user_type', 'user_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
