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
        Schema::table('sensor_readings', function (Blueprint $table) {
            // Drop old water_level column if it still exists
            if (Schema::hasColumn('sensor_readings', 'water_level')) {
                $table->dropColumn('water_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            // Recreate water_level as a nullable float in case of rollback
            if (! Schema::hasColumn('sensor_readings', 'water_level')) {
                $table->float('water_level', 8, 2)->nullable();
            }
        });
    }
};

