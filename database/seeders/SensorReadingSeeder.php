<?php

namespace Database\Seeders;

use App\Models\SensorReading;
use Illuminate\Database\Seeder;

use Carbon\Carbon;

class SensorReadingSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [85, 78, 72, 65, 58, 52, 47, 38, 25, 18, 15];

        foreach ($levels as $index => $level) {
            SensorReading::create([
                'uv_reading' => $level,
                'ip_address' => '192.168.1.10',
                'created_at' => Carbon::now()->subMinutes(120 - ($index * 10)),
                'updated_at' => Carbon::now()->subMinutes(120 - ($index * 10)),
            ]);
        }
    }
}