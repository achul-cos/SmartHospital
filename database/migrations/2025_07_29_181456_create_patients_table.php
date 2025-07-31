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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('card_number')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->string('password')->default(Hash::make('password'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
