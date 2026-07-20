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
    Schema::create('medicine_masters', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();

        $table->foreignId('medicine_category_id')
              ->constrained('medicine_categories');

        $table->foreignId('dose_master_id')
              ->constrained('dose_masters');

        $table->foreignId('start_time_id')
              ->constrained('start_times');

        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_masters');
    }
};
