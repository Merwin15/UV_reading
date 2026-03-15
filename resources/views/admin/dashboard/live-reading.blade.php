<div class="p-10">
    <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
        <svg class="w-8 h-8 text-green-700 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a9 9 0 019 9v5a3 3 0 01-3 3H6a3 3 0 01-3-3v-5a9 9 0 019-9z" />
        </svg>
        Live UV Reading
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
            <span class="text-xs font-bold text-yellow-700 mb-2 uppercase tracking-widest">UV Level</span>
            <span class="text-6xl font-black text-yellow-700 mb-1" id="live-uv-value">--</span>
            <span class="text-sm text-yellow-700 font-semibold">%</span>
            <span class="text-xs text-yellow-700 mt-3" id="live-update-time">Last update: --</span>
        </div>

        <!-- Status Alert Card -->
        <div id="status-card" class="rounded-2xl shadow-2xl p-10 flex flex-col items-center border-t-4 border-gray-300 bg-white">
            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-semibold text-gray-700 mb-3 uppercase tracking-widest">Status</span>
            <span class="text-4xl font-extrabold text-gray-400 mb-2" id="status-text">Loading...</span>
            <span class="text-xs text-gray-600 text-center" id="status-message">Fetching real-time data...</span>
        </div>

        <!-- Additional Stats -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-green-700 flex flex-col justify-between">
            <div class="mb-4">
                <p class="text-xs font-semibold text-green-700 mb-2">Today's Average</p>
                <p class="text-3xl font-bold text-green-700" id="avg-today">--</p>
                <p class="text-xs text-gray-600 mt-1">%</p>
            </div>
            <div class="mb-4">
                <p class="text-xs font-semibold text-blue-700 mb-2">Total Readings</p>
                <p class="text-3xl font-bold text-blue-700" id="total-readings">--</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-purple-700 mb-2">Critical Alerts</p>
                <p class="text-3xl font-bold text-purple-700" id="critical-readings">--</p>
            </div>
        </div>
    </div>

    <!-- Recent Readings in Real-Time -->
    <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-green-700">
        <h3 class="text-xl font-bold text-green-700 mb-6">Recent Readings Feed</h3>
        <div id="recent-feed" class="space-y-3">
            <p class="text-center text-gray-500 py-8">Loading readings...</p>
        </div>
    </div>
</div>

<script>
    function fetchLiveData() {
        fetch('/api/dashboard-data')
            .then(response => response.json())
            .then(data => {
                const uvLevel = parseFloat(data.current_level);
                
                // Update main UV value
                document.getElementById('live-uv-value').textContent = uvLevel.toFixed(1);
                document.getElementById('live-update-time').textContent = 'Last update: ' + data.last_update;

                // Determine status
                let status, statusColor, statusMessage, statusIcon;
                if (uvLevel === 0) {
                    status = 'NO DATA';
                    statusColor = 'bg-gray-50 border-gray-300';
                    statusMessage = 'Waiting for device readings...';
                    statusIcon = '⏳';
                } else if (uvLevel < 20) {
                    status = 'SAFE';
                    statusColor = 'bg-green-50 border-green-600';
                    statusMessage = 'UV levels are safe. Low exposure risk.';
                    statusIcon = '✓';
                } else if (uvLevel < 50) {
                    status = 'MODERATE';
                    statusColor = 'bg-yellow-50 border-yellow-600';
                    statusMessage = 'Use sun protection. Limit exposure.';
                    statusIcon = '⚠';
                } else if (uvLevel < 75) {
                    status = 'HIGH';
                    statusColor = 'bg-orange-50 border-orange-600';
                    statusMessage = 'High UV levels. Strong protection needed.';
                    statusIcon = '⚠';
                } else {
                    status = 'EXTREME';
                    statusColor = 'bg-red-50 border-red-600';
                    statusMessage = 'Extreme UV levels. Avoid exposure!';
                    statusIcon = '⚠️';
                }

                // Update status card
                const statusCard = document.getElementById('status-card');
                statusCard.className = `rounded-2xl shadow-2xl p-10 flex flex-col items-center border-t-4 ${statusColor}`;
                document.getElementById('status-text').textContent = status;
                document.getElementById('status-message').textContent = statusMessage;

                // Update stats
                document.getElementById('avg-today').textContent = data.avg_today;
                document.getElementById('total-readings').textContent = data.total_readings;
                document.getElementById('critical-readings').textContent = data.critical_readings;

                // Update recent feeds
                updateRecentFeed(data.recent_readings);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('live-uv-value').textContent = 'Error';
            });
    }

    function updateRecentFeed(readings) {
        const feed = document.getElementById('recent-feed');
        
        if (readings.length === 0) {
            feed.innerHTML = '<p class="text-center text-gray-500 py-8">No readings available yet</p>';
            return;
        }

        feed.innerHTML = readings.slice(0, 5).map(reading => {
            const time = new Date(reading.created_at).toLocaleString();
            const uvLevel = reading.uv_reading;
            
            let bgColor, progressColor, levelText;
            if (uvLevel < 20) {
                bgColor = 'bg-green-50 dark:bg-green-900';
                progressColor = 'bg-green-500';
                levelText = 'SAFE';
            } else if (uvLevel < 50) {
                bgColor = 'bg-yellow-50 dark:bg-yellow-900';
                progressColor = 'bg-yellow-500';
                levelText = 'MODERATE';
            } else if (uvLevel < 75) {
                bgColor = 'bg-orange-50 dark:bg-orange-900';
                progressColor = 'bg-orange-500';
                levelText = 'HIGH';
            } else {
                bgColor = 'bg-red-50 dark:bg-red-900';
                progressColor = 'bg-red-500';
                levelText = 'EXTREME';
            }

            return `
                <div class="p-4 rounded-lg ${bgColor} border-l-4 ${progressColor === 'bg-green-500' ? 'border-green-500' : progressColor === 'bg-yellow-500' ? 'border-yellow-500' : progressColor === 'bg-orange-500' ? 'border-orange-500' : 'border-red-500'} flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">${time}</p>
                        <div class="w-32 h-2 bg-gray-300 dark:bg-gray-700 rounded-full mt-2 overflow-hidden">
                            <div class="${progressColor} h-full transition-all" style="width: ${uvLevel}%"></div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-lg font-bold">${uvLevel.toFixed(1)}%</p>
                        <p class="text-xs font-semibold text-gray-600">${levelText}</p>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Initial load and auto-refresh every 3 seconds for real-time feel
    fetchLiveData();
    setInterval(fetchLiveData, 3000);
</script>

