<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    // Table name (optional if it follows Laravel convention)
    protected $table = 'sensor_readings';

    // Fillable fields for mass assignment
    protected $fillable = [
        'uv_reading',
        'ip_address',
    ];

    // Enable timestamps (created_at, updated_at)
    public $timestamps = true;

    // Cast attributes to specific types
    protected $casts = [
        'uv_reading' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Optional: Accessor for formatted UV reading
    public function getFormattedUvReadingAttribute()
    {
        return number_format($this->uv_reading, 2) . '%';
    }

    // Optional: Scope for recent readings
    public function scopeRecent($query, $minutes = 60)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }

    // Optional: Scope for critical levels
    public function scopeCriticalLevel($query, $threshold = 20)
    {
        return $query->where('uv_reading', '<=', $threshold);
    }
}