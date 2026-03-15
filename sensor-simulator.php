#!/usr/bin/env php
<?php

/**
 * UV Sensor Data Simulator - Quick Testing Tool
 * 
 * This script generates sample UV sensor readings for testing the frontend
 * without needing physical ESP32 hardware.
 * 
 * Usage:
 *   php sensor-simulator.php [number_of_readings]
 *   
 * Examples:
 *   php sensor-simulator.php         # Creates 20 random readings
 *   php sensor-simulator.php 50      # Creates 50 random readings
 *   php sensor-simulator.php --continuous  # Keeps adding readings every 10 seconds
 */

require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SensorReading;
use Carbon\Carbon;

// Determine mode and parameters
$mode = $argv[1] ?? 'single';
$count = is_numeric($argv[1] ?? null) ? (int)$argv[1] : 20;

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║    UV Sensor Data Simulator - Testing Tool               ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

if ($mode === '--continuous') {
    echo "Mode: CONTINUOUS - Generating readings every 10 seconds\n";
    echo "Press Ctrl+C to stop\n\n";
    
    $iteration = 0;
    while (true) {
        $iteration++;
        generateSingleReading($iteration);
        sleep(10);
    }
} elseif ($mode === '--bulk') {
    echo "Mode: BULK - Generating $count readings at once\n\n";
    generateBulkReadings($count);
} else {
    echo "Mode: SINGLE - Generating $count random readings\n\n";
    generateRandomReadings($count);
}

echo "\n";
echo "✓ Done! Check your dashboard to see the new readings.\n";
echo "  Visit: http://localhost:8000/dashboard\n\n";

// ============ Helper Functions ============

function generateRandomReadings($count = 20)
{
    $readings = [];
    $baseTime = now()->subMinutes($count * 2);
    
    for ($i = 0; $i < $count; $i++) {
        // Generate semi-realistic UV data (varies over time)
        $hour = now()->hour;
        $baseline = max(10, min(80, (sin(($hour - 6) * 0.3) * 30 + 35)));
        $variance = rand(-15, 15);
        $noise = (rand(-5, 5) / 10);
        
        $uvReading = max(0, min(100, $baseline + $variance + $noise));
        
        $reading = new SensorReading([
            'uv_reading' => round($uvReading, 2),
            'ip_address' => '192.168.1.' . rand(1, 254),
        ]);
        
        $reading->created_at = $baseTime->copy()->addMinutes($i * 2);
        $reading->updated_at = $reading->created_at;
        
        $reading->save();
        $readings[] = $reading;
        
        echo sprintf(
            "  Created: ID=%d | UV=%.1f%% | Time=%s\n",
            $reading->id,
            $reading->uv_reading,
            $reading->created_at->format('Y-m-d H:i:s')
        );
    }
    
    echo "\n✓ Generated $count readings\n";
    return $readings;
}

function generateSingleReading($iteration = 1)
{
    // Simulate varying UV levels throughout the day
    $hour = now()->hour;
    $dayProgress = ($hour - 6) / 12; // 6 AM to 6 PM peak
    
    // Peak UV around noon (12 PM)
    $baseline = 50 * sin(deg2rad($dayProgress * 180));
    $baseline = max(5, $baseline); // Minimum 5%
    
    $variance = rand(-10, 10);
    $uvReading = max(0, min(100, $baseline + $variance));
    
    $reading = new SensorReading([
        'uv_reading' => round($uvReading, 2),
        'ip_address' => '192.168.1.' . rand(1, 254),
    ]);
    
    $reading->save();
    
    echo sprintf(
        "  [%s] Created reading #%d: UV=%.1f%% | Time=%s\n",
        now()->format('H:i:s'),
        $iteration,
        $reading->uv_reading,
        $reading->created_at->format('Y-m-d H:i:s')
    );
}

function generateBulkReadings($count = 100)
{
    $readings = [];
    $baseTime = now()->subHours(24);
    
    for ($i = 0; $i < $count; $i++) {
        $timestamp = $baseTime->copy()->addMinutes($i * (24 * 60 / $count));
        $hour = $timestamp->hour;
        
        // Simulate realistic daily UV pattern
        $dayProgress = ($hour - 6) / 12;
        if ($dayProgress < 0 || $dayProgress > 2) {
            $baseline = 5; // Night time
        } else {
            $baseline = 50 * sin(deg2rad($dayProgress * 180)); // Peak at noon
        }
        
        $variance = rand(-20, 20);
        $uvReading = max(0, min(100, $baseline + $variance));
        
        $reading = new SensorReading([
            'uv_reading' => round($uvReading, 2),
            'ip_address' => '192.168.1.' . rand(1, 254),
        ]);
        
        $reading->created_at = $timestamp;
        $reading->updated_at = $timestamp;
        $reading->save();
        
        $readings[] = $reading;
        
        if (($i + 1) % 10 === 0) {
            echo "  Created " . ($i + 1) . " readings...\n";
        }
    }
    
    $avg = SensorReading::avg('uv_reading');
    $max = SensorReading::max('uv_reading');
    $min = SensorReading::min('uv_reading');
    
    echo "\n✓ Successfully generated $count readings\n";
    echo "  Average UV: " . round($avg, 2) . "%\n";
    echo "  Maximum UV: " . round($max, 2) . "%\n";
    echo "  Minimum UV: " . round($min, 2) . "%\n";
    echo "  Total Readings: " . SensorReading::count() . "\n";
}

/**
 * Display help message
 */
function showHelp()
{
    echo "Usage: php sensor-simulator.php [OPTION] [COUNT]\n\n";
    echo "Options:\n";
    echo "  (default)      Generate COUNT random readings (default 20)\n";
    echo "  --continuous   Keep generating 1 reading every 10 seconds\n";
    echo "  --bulk         Generate COUNT readings spanning 24 hours\n";
    echo "  --clear        Delete all sensor readings from database\n";
    echo "  --help         Show this help message\n\n";
    echo "Examples:\n";
    echo "  php sensor-simulator.php              # 20 random readings\n";
    echo "  php sensor-simulator.php 50           # 50 random readings\n";
    echo "  php sensor-simulator.php --bulk 100   # 100 readings over 24h\n";
    echo "  php sensor-simulator.php --continuous # Continuous mode\n";
}
