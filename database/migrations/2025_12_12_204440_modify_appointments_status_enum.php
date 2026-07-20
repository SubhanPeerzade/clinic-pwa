<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'arrived' into enum list (keep existing values)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE `appointments`
                MODIFY `status` ENUM('waiting','arrived','called','in_consultation','seen','cancelled')
                NOT NULL DEFAULT 'waiting'
            ");
        }
    }

    public function down(): void
    {
        // Revert to previous enum (remove 'arrived')
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE `appointments`
                MODIFY `status` ENUM('waiting','called','in_consultation','seen','cancelled')
                NOT NULL DEFAULT 'waiting'
            ");
        }
    }
};
