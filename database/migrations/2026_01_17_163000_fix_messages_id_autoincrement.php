<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run for MySQL where AUTO_INCREMENT is supported
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver !== 'mysql' && $driver !== 'mysqli') {
            return;
        }

        // Attempt to set id as BIGINT UNSIGNED AUTO_INCREMENT
        try {
            // Use MODIFY to set AUTO_INCREMENT without changing type if possible
            DB::statement("ALTER TABLE `messages` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
        } catch (\Exception $e) {
            // Fallback: try INT
            try {
                DB::statement("ALTER TABLE `messages` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT");
            } catch (\Exception $e) {
                // Log and ignore — we'll surface migration error if needed
                \Illuminate\Support\Facades\Log::error('Failed to set AUTO_INCREMENT on messages.id: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reverting automatically because changing AUTO_INCREMENT off is risky
    }
};
