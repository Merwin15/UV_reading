<div class="p-10">
    <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
        <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M3 10h18M3 15h18M3 20h18" />
        </svg>
        All Sensor Readings
    </h2>

    <div class="mb-6 flex justify-end">
        <form method="POST" action="{{ route('admin.sensor-readings.clear') }}" onsubmit="return confirm('Are you sure you want to wipe all sensor data? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700 shadow-sm">
                Clear All Sensor Data
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-green-100 overflow-hidden">
        <div class="max-h-[520px] overflow-y-auto">
            <table class="min-w-full divide-y divide-green-100 text-sm">
                <thead class="bg-green-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-green-800">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-green-800">Timestamp</th>
                        <th class="px-4 py-3 text-left font-semibold text-green-800">Heat (°)</th>
                        <th class="px-4 py-3 text-left font-semibold text-green-800">UV Index</th>
                        <th class="px-4 py-3 text-left font-semibold text-green-800">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-green-800">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-green-50">
                    @forelse($readings as $reading)
                        @php
                            $value = $reading->uv_reading;
                            $uvIndex = round($value / 9);
                            if ($value < 25) {
                                $status = 'Safe';
                                $badgeClasses = 'bg-green-100 text-green-700';
                            } elseif ($value <= 60) {
                                $status = 'Moderate';
                                $badgeClasses = 'bg-yellow-100 text-yellow-700';
                            } else {
                                $status = 'Danger';
                                $badgeClasses = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <tr class="hover:bg-green-50/60">
                            <td class="px-4 py-2 text-green-900">{{ $reading->id }}</td>
                            <td class="px-4 py-2 text-green-900">{{ $reading->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-2 text-green-900">{{ number_format($value, 2) }}°</td>
                            <td class="px-4 py-2 text-green-900">{{ $uvIndex }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-green-900">{{ $reading->ip_address ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-green-700">
                                No sensor readings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

