<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'uv_reading' => 'required|numeric|min:0|max:100'
            ]);

            $reading = SensorReading::create([
                'uv_reading' => $validated['uv_reading'],
                'ip_address' => $request->ip()
            ]);

            Log::info('Sensor data saved to database', [
                'id' => $reading->id,
                'uv_reading' => $reading->uv_reading,
                'ip' => $reading->ip_address
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully',
                'data' => $reading
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error saving sensor data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    // NEW: Get dashboard statistics
    public function getDashboardData()
    {
        $latestReading = SensorReading::latest()->first();
        $avgToday = SensorReading::whereDate('created_at', today())->avg('uv_reading');
        $totalReadings = SensorReading::count();
        $criticalReadings = SensorReading::where('uv_reading', '<', 20)->count();
        
        // Get last 10 readings for chart
        $recentReadings = SensorReading::latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'current_level' => $latestReading ? round($latestReading->uv_reading, 2) : 0,
            'avg_today' => round($avgToday ?? 0, 2),
            'total_readings' => $totalReadings,
            'critical_readings' => $criticalReadings,
            'recent_readings' => $recentReadings,
            'last_update' => $latestReading ? $latestReading->created_at->diffForHumans() : 'No data',
        ]);
    }
}