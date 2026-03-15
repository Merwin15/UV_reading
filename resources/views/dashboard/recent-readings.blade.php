<div class="p-8">
    <h2 class="text-2xl font-semibold text-indigo-600 dark:text-indigo-300 mb-6">Recent Readings</h2>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Latest Reading</p>
            <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mt-2" id="latest-reading">--</p>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-2">Updated: <span id="updated-time">--</span></p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm">Health Status</p>
            <p class="text-2xl font-bold mt-2" id="health-status">
                <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                <span id="status-text">Monitoring</span>
            </p>
        </div>
    </div>

    <!-- Readings List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Last 10 Readings</h3>
        <div id="readings-list" class="space-y-2">
            <p class="text-center text-gray-500 py-8">Loading readings...</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Daily Statistics</h3>
        <div id="statistics" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4">
                <p class="text-gray-600 dark:text-gray-400 text-sm">Highest</p>
                <p class="text-2xl font-bold text-red-500 mt-1" id="stat-highest">--</p>
            </div>
            <div class="text-center p-4">
                <p class="text-gray-600 dark:text-gray-400 text-sm">Average</p>
                <p class="text-2xl font-bold text-blue-500 mt-1" id="stat-average">--</p>
            </div>
            <div class="text-center p-4">
                <p class="text-gray-600 dark:text-gray-400 text-sm">Lowest</p>
                <p class="text-2xl font-bold text-green-500 mt-1" id="stat-lowest">--</p>
            </div>
            <div class="text-center p-4">
                <p class="text-gray-600 dark:text-gray-400 text-sm">Total Count</p>
                <p class="text-2xl font-bold text-indigo-500 mt-1" id="stat-count">--</p>
            </div>
        </div>
    </div>
</div>

<script>
    function fetchRecentReadings() {
        fetch('/api/dashboard-data')
            .then(response => response.json())
            .then(data => {
                // Update latest reading
                document.getElementById('latest-reading').textContent = data.current_level + '%';
                document.getElementById('updated-time').textContent = data.last_update;

                // Update health status
                const statusElement = document.getElementById('status-text');
                const statusDot = document.querySelector('#health-status span:first-child');
                
                const currentLevel = parseFloat(data.current_level);
                if (currentLevel === 0) {
                    statusElement.textContent = 'No Data';
                    statusDot.className = 'inline-block w-3 h-3 bg-gray-500 rounded-full mr-2';
                } else if (currentLevel < 20) {
                    statusElement.textContent = 'Safe';
                    statusDot.className = 'inline-block w-3 h-3 bg-green-500 rounded-full mr-2';
                } else if (currentLevel < 50) {
                    statusElement.textContent = 'Moderate';
                    statusDot.className = 'inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2';
                } else if (currentLevel < 75) {
                    statusElement.textContent = 'High';
                    statusDot.className = 'inline-block w-3 h-3 bg-orange-500 rounded-full mr-2';
                } else {
                    statusElement.textContent = 'Extreme';
                    statusDot.className = 'inline-block w-3 h-3 bg-red-500 rounded-full mr-2';
                }

                // Update readings list
                updateReadingsList(data.recent_readings);

                // Update statistics
                updateStatistics(data.recent_readings);
            })
            .catch(error => console.error('Error:', error));
    }

    function updateReadingsList(readings) {
        const readingsList = document.getElementById('readings-list');
        
        if (readings.length === 0) {
            readingsList.innerHTML = '<p class="text-center text-gray-500 py-8">No readings available</p>';
            return;
        }

        readingsList.innerHTML = readings.map(reading => {
            const time = new Date(reading.created_at).toLocaleString();
            const uvLevel = reading.uv_reading;
            
            let bgColor, progressColor;
            if (uvLevel < 20) {
                bgColor = 'bg-green-50 dark:bg-green-900';
                progressColor = 'bg-green-500';
            } else if (uvLevel < 50) {
                bgColor = 'bg-yellow-50 dark:bg-yellow-900';
                progressColor = 'bg-yellow-500';
            } else if (uvLevel < 75) {
                bgColor = 'bg-orange-50 dark:bg-orange-900';
                progressColor = 'bg-orange-500';
            } else {
                bgColor = 'bg-red-50 dark:bg-red-900';
                progressColor = 'bg-red-500';
            }

            return `
                <div class="p-3 rounded-lg ${bgColor} flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">${time}</p>
                        <div class="w-24 h-2 bg-gray-300 dark:bg-gray-700 rounded-full mt-1 overflow-hidden">
                            <div class="${progressColor} h-full" style="width: ${uvLevel}%"></div>
                        </div>
                    </div>
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">${uvLevel.toFixed(1)}%</p>
                </div>
            `;
        }).join('');
    }

    function updateStatistics(readings) {
        if (readings.length === 0) {
            document.getElementById('stat-highest').textContent = '--';
            document.getElementById('stat-average').textContent = '--';
            document.getElementById('stat-lowest').textContent = '--';
            document.getElementById('stat-count').textContent = '0';
            return;
        }

        const values = readings.map(r => r.uv_reading);
        const highest = Math.max(...values).toFixed(1);
        const lowest = Math.min(...values).toFixed(1);
        const average = (values.reduce((a, b) => a + b, 0) / values.length).toFixed(1);

        document.getElementById('stat-highest').textContent = highest + '%';
        document.getElementById('stat-average').textContent = average + '%';
        document.getElementById('stat-lowest').textContent = lowest + '%';
        document.getElementById('stat-count').textContent = readings.length;
    }

    // Initial load and refresh every 10 seconds
    fetchRecentReadings();
    setInterval(fetchRecentReadings, 10000);
</script>
