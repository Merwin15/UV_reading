<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SensorReading;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $readings = SensorReading::orderBy('created_at', 'desc')->get();

        // For chart: last 24 readings
        $history = SensorReading::orderBy('created_at', 'desc')->take(24)->get();

        // For stats
        $current = $readings->first();
        $avgToday = SensorReading::whereDate('created_at', now())->avg('uv_reading');
        // Derive UV index (0–11+) from average reading
        $avgUvIndex = $avgToday !== null ? round($avgToday / 9) : null;
        $total = $readings->count();
        $critical = $readings->where('uv_reading', '<', 20)->count();
        $lastUpdate = $current ? $current->created_at->diffForHumans() : 'N/A';

        // Categorized UV / heat levels
        // Thresholds: <25 Safe, 25–60 Moderate, >60 Danger
        $safeCount = SensorReading::where('uv_reading', '<', 25)->count();
        $moderateCount = SensorReading::whereBetween('uv_reading', [25, 60])->count();
        $dangerCount = SensorReading::where('uv_reading', '>', 60)->count();

        // Live reading logic (1-hour delayed)
        $cutoff = now()->subHour();
        $liveReading = SensorReading::where('created_at', '<=', $cutoff)
            ->orderBy('created_at', 'desc')
            ->first();

        $liveStatus = null;
        if ($liveReading) {
            $value = $liveReading->uv_reading;
            if ($value < 25) {
                $liveStatus = 'Safe';
            } elseif ($value <= 60) {
                $liveStatus = 'Moderate';
            } else {
                $liveStatus = 'Danger';
            }
        }

        return view('admin.dashboard', [
            'readings'      => $readings,
            'history'       => $history,
            'current'       => $current,
            'avgToday'      => $avgToday,
            'avgUvIndex'    => $avgUvIndex,
            'total'         => $total,
            'critical'      => $critical,
            'lastUpdate'    => $lastUpdate,
            'safeCount'     => $safeCount,
            'moderateCount' => $moderateCount,
            'dangerCount'   => $dangerCount,
            'liveReading'   => $liveReading,
            'liveStatus'    => $liveStatus,
            'cutoff'        => $cutoff,
        ]);
    }

    public function clearSensorReadings(Request $request)
    {
        SensorReading::truncate();

        return back()->with('status', 'All sensor readings have been wiped.');
    }
}