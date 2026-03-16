<div class="p-10">
    <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
        <svg class="w-8 h-8 text-green-700 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a9 9 0 019 9v5a3 3 0 01-3 3H6a3 3 0 01-3-3v-5a9 9 0 019-9z" />
        </svg>
        Live UV & Heat Readings
    </h2>

    <!-- Main Live Display -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <!-- Current UV Reading (Big Display) -->
        <div class="bg-gradient-to-br from-yellow-100 to-orange-100 rounded-2xl shadow-2xl p-10 flex flex-col items-center border-t-4 border-yellow-600 transform hover:scale-105 transition">
            <div class="animate-pulse mb-2">
                <svg class="w-12 h-12 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3" stroke="currentColor" stroke-width="2"/>
                    <line x1="12" y1="21" x2="12" y2="23" stroke="currentColor" stroke-width="2"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke="currentColor" stroke-width="2"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke="currentColor" stroke-width="2"/>
                    <line x1="1" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2"/>
                    <line x1="21" y1="12" x2="23" y2="12" stroke="currentColor" stroke-width="2"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke="currentColor" stroke-width="2"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
            <span class="text-xs font-bold text-yellow-700 mb-2 uppercase tracking-widest">UV Level (Index)</span>
            <span class="text-6xl font-black text-yellow-700 mb-1" id="live-uv-value">--</span>
            <span class="text-xs text-yellow-700 mt-3" id="live-update-time">Last update: --</span>
        </div>

        <!-- Current HEAT Reading -->
        <div class="bg-gradient-to-br from-red-100 to-orange-100 rounded-2xl shadow-2xl p-10 flex flex-col items-center border-t-4 border-red-600 transform hover:scale-105 transition">
            <div class="mb-2">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a4 4 0 00-2 7.465V17a2 2 0 104 0v-6.535A4 4 0 0012 3z" />
                </svg>
            </div>
            <span class="text-xs font-bold text-red-700 mb-2 uppercase tracking-widest">Heat Level</span>
            <span class="text-6xl font-black text-red-700 mb-1" id="live-heat-value">--</span>
            <span class="text-sm text-red-700 font-semibold">°C</span>
        </div>

        <!-- Status + Summary Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-10 border-t-4 border-gray-300 flex flex-col justify-between" id="status-card">
            <div class="flex flex-col items-center mb-6">
                <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-200 mb-2 uppercase tracking-widest">Status</span>
                <span class="text-3xl font-extrabold text-gray-400 dark:text-gray-300 mb-1" id="status-text">Loading...</span>
                <span class="text-xs text-gray-600 dark:text-gray-400 text-center" id="status-message">
                    Fetching real-time data...
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                <div>
                    <p class="text-xs font-semibold text-green-700 dark:text-green-300 mb-1">UV Today (avg)</p>
                    <p class="text-xl font-bold text-green-700 dark:text-green-200" id="avg-uv-today">--</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-red-700 dark:text-red-300 mb-1">Heat Today (avg)</p>
                    <p class="text-xl font-bold text-red-700 dark:text-red-200" id="avg-heat-today">--</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 mb-1">Total Readings</p>
                    <p class="text-xl font-bold text-blue-700 dark:text-blue-200" id="total-readings">--</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-purple-700 dark:text-purple-300 mb-1">Critical Alerts</p>
                    <p class="text-xl font-bold text-purple-700 dark:text-purple-200" id="critical-readings">--</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Readings in Real-Time -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 border-t-4 border-green-700">
        <h3 class="text-xl font-bold text-green-700 dark:text-green-200 mb-6">Recent Readings Feed</h3>
        <div id="recent-feed" class="space-y-3">
            <p class="text-center text-gray-500 dark:text-gray-400 py-8">Loading readings...</p>
        </div>
    </div>
</div>

<!-- Danger Toast (bottom-right) -->
<div
    id="danger-toast"
    class="fixed bottom-4 right-4 z-50 max-w-sm w-full sm:w-96 transform transition-all duration-300 translate-y-10 opacity-0 pointer-events-none"
>
    <div class="bg-red-50 dark:bg-red-900/90 border border-red-300 dark:border-red-700 rounded-2xl shadow-2xl px-5 py-4 flex items-start gap-3">
        <div class="mt-1">
            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-red-800 dark:text-red-100">
                Danger: Take Shelter
            </p>
            <p class="text-xs text-red-700 dark:text-red-200 mt-1" id="danger-toast-message">
                UV or heat levels are in the danger zone. Farmers should take shelter or rest until conditions improve.
            </p>
        </div>
        <button
            type="button"
            class="ml-2 text-red-500 dark:text-red-200 hover:text-red-700 dark:hover:text-red-100 text-sm font-bold"
            onclick="hideDangerToast()"
        >
            ✕
        </button>
    </div>
</div>

<script>
    let dangerToastTimeout = null;

    function showDangerToast(message) {
        const toast = document.getElementById('danger-toast');
        const msgEl = document.getElementById('danger-toast-message');

        if (!toast || !msgEl) return;

        if (message) {
            msgEl.textContent = message;
        }

        // Clear previous timeout
        if (dangerToastTimeout) {
            clearTimeout(dangerToastTimeout);
            dangerToastTimeout = null;
        }

        // Show toast
        toast.classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');

        // Auto-hide after 3 seconds
        dangerToastTimeout = setTimeout(() => {
            hideDangerToast();
        }, 3000);
    }

    function hideDangerToast() {
        const toast = document.getElementById('danger-toast');
        if (!toast) return;

        toast.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }

    function fetchLiveData() {
        fetch('/api/dashboard-data')
            .then(response => response.json())
            .then(data => {
                console.log('dashboard-data:', data);

                // Read UV from either current_uv OR legacy current_level
                const uvLevel   = parseFloat(
                    data.current_uv   !== undefined
                        ? data.current_uv
                        : (data.current_level !== undefined ? data.current_level : 0)
                );
                const heatLevel = parseFloat(data.current_heat ?? 0);

                // Update main UV and heat values
                document.getElementById('live-uv-value').textContent   = isFinite(uvLevel)   ? uvLevel.toFixed(1)   : '--';
                document.getElementById('live-heat-value').textContent = isFinite(heatLevel) ? heatLevel.toFixed(1) : '--';
                document.getElementById('live-update-time').textContent = 'Last update: ' + (data.last_update ?? 'No data');

                // Status based primarily on UV, as you requested
                let status, statusColor, statusMessage;
                if (!isFinite(uvLevel) || uvLevel === 0) {
                    status = 'NO DATA';
                    statusColor = 'bg-gray-50 border-gray-300';
                    statusMessage = 'Waiting for device readings...';
                } else if (uvLevel < 3) {
                    status = 'SAFE';
                    statusColor = 'bg-green-50 border-green-600';
                    statusMessage = 'UV levels are safe.';
                } else if (uvLevel < 7) {
                    status = 'MODERATE';
                    statusColor = 'bg-yellow-50 border-yellow-600';
                    statusMessage = 'Use protection; limit exposure.';
                } else if (uvLevel < 9) {
                    status = 'HIGH';
                    statusColor = 'bg-orange-50 border-orange-600';
                    statusMessage = 'High UV levels. Strong protection needed.';
                    showDangerToast('UV levels are high. Farmers should limit exposure and use strong protection.');
                } else {
                    status = 'EXTREME';
                    statusColor = 'bg-red-50 border-red-600';
                    statusMessage = 'Extreme UV levels. Avoid exposure!';
                    showDangerToast('Extreme UV levels detected. Farmers should take shelter or rest immediately.');
                }

                // Update status card style and text
                const statusCard = document.getElementById('status-card');
                statusCard.className = `bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-10 flex flex-col justify-between border-t-4 ${statusColor}`;
                document.getElementById('status-text').textContent = status;
                document.getElementById('status-message').textContent = statusMessage;

                // Update summary stats
                document.getElementById('avg-uv-today').textContent   = data.avg_today_uv   !== undefined ? data.avg_today_uv   : (data.avg_today ?? '--');
                document.getElementById('avg-heat-today').textContent = data.avg_today_heat !== undefined ? data.avg_today_heat : '--';
                document.getElementById('total-readings').textContent = data.total_readings ?? '--';
                document.getElementById('critical-readings').textContent = data.critical_readings ?? '--';

                // Update recent feeds
                updateRecentFeed(data.recent_readings || []);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('live-uv-value').textContent = 'Error';
            });
    }

    function updateRecentFeed(readings) {
        const feed = document.getElementById('recent-feed');

        if (!readings || readings.length === 0) {
            feed.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-8">No readings available yet</p>';
            return;
        }

        feed.innerHTML = readings.slice(0, 5).map(reading => {
            const time      = new Date(reading.created_at).toLocaleString();
            const uvLevel   = parseFloat(reading.uv_reading ?? 0);
            const heatLevel = parseFloat(reading.heat_reading ?? 0);

            let bgColor, progressColor, levelText;
            if (uvLevel < 3) {
                bgColor = 'bg-green-50 dark:bg-green-900/40';
                progressColor = 'bg-green-500';
                levelText = 'SAFE';
            } else if (uvLevel < 7) {
                bgColor = 'bg-yellow-50 dark:bg-yellow-900/40';
                progressColor = 'bg-yellow-500';
                levelText = 'MODERATE';
            } else if (uvLevel < 9) {
                bgColor = 'bg-orange-50 dark:bg-orange-900/40';
                progressColor = 'bg-orange-500';
                levelText = 'HIGH';
            } else {
                bgColor = 'bg-red-50 dark:bg-red-900/40';
                progressColor = 'bg-red-500';
                levelText = 'EXTREME';
            }

            return `
                <div class="p-4 rounded-lg ${bgColor} border-l-4 ${progressColor === 'bg-green-500' ? 'border-green-500' : progressColor === 'bg-yellow-500' ? 'border-yellow-500' : progressColor === 'bg-orange-500' ? 'border-orange-500' : 'border-red-500'} flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">${time}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            UV: ${isFinite(uvLevel) ? uvLevel.toFixed(1) : '--'}
                            &nbsp;|&nbsp;
                            Heat: ${isFinite(heatLevel) ? heatLevel.toFixed(1) + '°C' : '--'}
                        </p>
                        <div class="w-32 h-2 bg-gray-300 dark:bg-gray-700 rounded-full mt-2 overflow-hidden">
                            <div class="${progressColor} h-full transition-all" style="width: ${Math.max(0, Math.min(uvLevel, 100))}%"></div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100">${isFinite(uvLevel) ? uvLevel.toFixed(1) : '--'}</p>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400">${levelText}</p>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Initial load and auto-refresh every 3 seconds for real-time feel
    fetchLiveData();
    setInterval(fetchLiveData, 3000);
</script>