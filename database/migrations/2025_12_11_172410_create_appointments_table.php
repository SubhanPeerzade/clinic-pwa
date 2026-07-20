<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->string('patient_name')->nullable(); // store name for quick display (denormalized)
            $table->string('patient_phone')->nullable();
            $table->date('appointment_date')->index();
            $table->unsignedInteger('token')->nullable()->index(); // token number for the day
            $table->enum('status', ['waiting','called','in_consultation','seen','cancelled'])->default('waiting')->index();
            $table->unsignedBigInteger('doctor_id')->nullable()->index(); // optional: assign to doctor
            $table->timestamp('called_at')->nullable();
            $table->timestamps();

            // Unique constraint: token must be unique PER date PER doctor (if doctor-specific)
            // For general queue (no doctor), unique per date:
            $table->unique(['appointment_date','token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

