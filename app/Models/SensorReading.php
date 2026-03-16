<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $table = 'sensor_readings';

    protected $fillable = [
        'uv_reading',
        'heat_reading',
        'ip_address',
    ];

    public $timestamps = true;

    protected $casts = [
        'uv_reading'   => 'float',
        'heat_reading' => 'float',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function getFormattedUvIndexAttribute()
    {
        return $this->uv_reading !== null
            ? number_format($this->uv_reading, 1)
            : null;
    }

    public function getFormattedHeatAttribute()
    {
        return $this->heat_reading !== null
            ? number_format($this->heat_reading, 1) . '°'
            : null;
    }

    public function scopeRecent($query, $minutes = 60)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeCriticalLevel($query, $uvThreshold = 8, $heatThreshold = 35)
    {
        return $query->where(function ($q) use ($uvThreshold, $heatThreshold) {
            $q->where('uv_reading', '>=', $uvThreshold)
              ->orWhere('heat_reading', '>=', $heatThreshold);
        });
    }
}