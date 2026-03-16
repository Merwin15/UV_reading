<div class="p-10">
    <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
        <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M3 10h18M3 15h18M3 20h18" />
        </svg>
        All Sensor Readings
    </h2>

    <!-- Top controls: pagination + actions -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <!-- Pagination at the top -->
        <div>
            @if ($readings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="inline-block rounded-lg border border-green-100 bg-green-50 px-3 py-2 text-xs text-green-800">
                    {{ $readings->onEachSide(1)->links() }}
                </div>
            @endif
        </div>

        <!-- Actions: report + clear -->
        <div class="flex items-center gap-3 justify-end">
            <a
                href="{{ route('admin.sensor-readings.report') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-green-600 text-white hover:bg-green-700 shadow-sm"
            >
                Generate Excel Report
            </a>

            <form method="POST" action="{{ route('admin.sensor-readings.clear') }}" onsubmit="return confirm('Are you sure you want to wipe all sensor data? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700 shadow-sm">
                    Clear All Sensor Data
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-green-100 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-green-100 dark:divide-gray-700 text-sm">
            <thead class="bg-green-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-green-800 dark:text-green-200">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-green-800 dark:text-green-200">Timestamp</th>
                    <th class="px-4 py-3 text-left font-semibold text-green-800 dark:text-green-200">Heat (°)</th>
                    <th class="px-4 py-3 text-left font-semibold text-green-800 dark:text-green-200">UV Index</th>
                    <th class="px-4 py-3 text-left font-semibold text-green-800 dark:text-green-200">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-green-800 dark:text-green-200">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-green-50 dark:divide-gray-800">
                @forelse($readings as $reading)
                    @php
                        $uv   = $reading->uv_reading;
                        $heat = $reading->heat_reading;

                        if ($uv !== null || $heat !== null) {
                            if ($uv >= 7 || $heat >= 35) {
                                $status = 'Danger';
                                $badgeClasses = 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200';
                            } elseif ($uv >= 3 || $heat >= 30) {
                                $status = 'Moderate';
                                $badgeClasses = 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200';
                            } else {
                                $status = 'Safe';
                                $badgeClasses = 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200';
                            }
                        } else {
                            $status = 'N/A';
                            $badgeClasses = 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300';
                        }
                    @endphp

                    <tr class="hover:bg-green-50/60 dark:hover:bg-gray-800/60">
                        <td class="px-4 py-2 text-green-900 dark:text-gray-100">{{ $reading->id }}</td>
                        <td class="px-4 py-2 text-green-900 dark:text-gray-100">
                            {{ $reading->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-4 py-2 text-green-900 dark:text-gray-100">
                            {{ $heat !== null ? number_format($heat, 2) . '°' : '—' }}
                        </td>
                        <td class="px-4 py-2 text-green-900 dark:text-gray-100">
                            {{ $uv !== null ? number_format($uv, 2) : '—' }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-green-900 dark:text-gray-100">
                            {{ $reading->ip_address ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-green-700 dark:text-gray-300">
                            No sensor readings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>