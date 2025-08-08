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
        Schema::create('revenue', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->integer('appointment_count')->default(0);
            $table->decimal('consultation_fees', 10, 2)->default(0);
            $table->decimal('medication_fees', 10, 2)->default(0);
            $table->decimal('procedure_fees', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure one revenue record per day
            $table->unique('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue');
    }
};
