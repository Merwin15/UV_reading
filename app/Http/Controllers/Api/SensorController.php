<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        try {
            // UV is REQUIRED; HEAT is OPTIONAL for now so it never blocks saving
            $validated = $request->validate([
                'uv_reading'   => 'required|numeric|min:0',  // UV index or %
                'heat_reading' => 'nullable|numeric',       // °C or °F
            ]);

            $reading = SensorReading::create([
                'uv_reading'   => $validated['uv_reading'],
                'heat_reading' => $validated['heat_reading'] ?? null,
                'ip_address'   => $request->ip(),
            ]);

            Log::info('Sensor data saved to database', [
                'id'           => $reading->id,
                'uv_reading'   => $reading->uv_reading,
                'heat_reading' => $reading->heat_reading,
                'ip'           => $reading->ip_address,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully',
                'data'    => $reading,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error saving sensor data', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: '.$e->getMessage(),
            ], 500);
        }
    }

    // Dashboard data for Live Reading + charts
    public function getDashboardData()
    {
        $latestReading = SensorReading::latest()->first();

        $avgTodayUv   = SensorReading::whereDate('created_at', today())->avg('uv_reading');
        $avgTodayHeat = SensorReading::whereDate('created_at', today())->avg('heat_reading');
        $totalReadings   = SensorReading::count();
        $criticalReadings = SensorReading::criticalLevel()->count();

        $recentReadings = SensorReading::latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            // Keep both names for backward compatibility: current_level and current_uv
            'current_level'     => $latestReading ? round($latestReading->uv_reading, 2) : 0,
            'current_uv'        => $latestReading ? round($latestReading->uv_reading, 2) : 0,
            'current_heat'      => $latestReading ? round($latestReading->heat_reading ?? 0, 2) : 0,
            'avg_today_uv'      => round($avgTodayUv ?? 0, 2),
            'avg_today_heat'    => round($avgTodayHeat ?? 0, 2),
            'total_readings'    => $totalReadings,
            'critical_readings' => $criticalReadings,
            'recent_readings'   => $recentReadings,
            'last_update'       => $latestReading ? $latestReading->created_at->diffForHumans() : 'No data',
        ]);
    }
}