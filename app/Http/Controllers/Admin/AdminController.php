<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SensorReading;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $readings = SensorReading::orderBy('created_at', 'desc')->paginate(20);

        // For chart: last 24 readings
        $history = SensorReading::orderBy('created_at', 'desc')->take(24)->get();

        // For stats
        $current      = $readings->first();
        $avgTodayUv   = SensorReading::whereDate('created_at', now())->avg('uv_reading');
        $avgTodayHeat = SensorReading::whereDate('created_at', now())->avg('heat_reading');
        $avgUvIndex   = $avgTodayUv !== null ? round($avgTodayUv / 9) : null;

        $total    = SensorReading::count();
        $critical = SensorReading::criticalLevel()->count();
        $lastUpdate = $current ? $current->created_at->diffForHumans() : 'N/A';

        // Categorized levels by UV index
        $safeCount = SensorReading::where('uv_reading', '<', 3)->count();
        $moderateCount = SensorReading::whereBetween('uv_reading', [3, 7])->count();
        $dangerCount = SensorReading::where('uv_reading', '>=', 7)->count();

        // Live reading logic (1-hour delayed)
        $cutoff = now()->subHour();
        $liveReading = SensorReading::where('created_at', '<=', $cutoff)
            ->orderBy('created_at', 'desc')
            ->first();

        $liveStatus = null;
        if ($liveReading) {
            $uv   = $liveReading->uv_reading;
            $heat = $liveReading->heat_reading;

            if ($uv >= 7 || $heat >= 35) {
                $liveStatus = 'Danger';
            } elseif ($uv >= 3 || $heat >= 30) {
                $liveStatus = 'Moderate';
            } else {
                $liveStatus = 'Safe';
            }
        }

        return view('admin.dashboard', [
            'readings'      => $readings,
            'history'       => $history,
            'current'       => $current,
            'avgToday'      => $avgTodayHeat, // your “Average Heat Today” card
            'avgTodayUv'    => $avgTodayUv,
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