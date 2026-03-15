<div class="p-8">
    <h2 class="text-2xl font-semibold text-indigo-600 dark:text-indigo-300 mb-6">UV Reading History</h2>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Current Level -->
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow p-6">
            <p class="text-white text-sm font-medium opacity-90">Current UV Level</p>
            <p class="text-3xl font-bold text-white mt-2" id="current-level">--</p>
            <p class="text-white text-xs mt-2" id="last-update">Loading...</p>
        </div>
        
        <!-- Average Today -->
        <div class="bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl shadow p-6">
            <p class="text-white text-sm font-medium opacity-90">Today's Average</p>
            <p class="text-3xl font-bold text-white mt-2" id="avg-today">--</p>
        </div>
        
        <!-- Total Readings -->
        <div class="bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl shadow p-6">
            <p class="text-white text-sm font-medium opacity-90">Total Readings</p>
            <p class="text-3xl font-bold text-white mt-2" id="total-readings">--</p>
        </div>
        
        <!-- Critical Readings -->
        <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-xl shadow p-6">
            <p class="text-white text-sm font-medium opacity-90">Critical Readings</p>
            <p class="text-3xl font-bold text-white mt-2" id="critical-readings">--</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">UV Level Trend</h3>
        <canvas id="chartCanvas" height="80"></canvas>
    </div>

    <!-- Recent Readings Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Readings</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-600">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Time</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">UV Reading</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Level</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody id="readings-tbody">
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">Loading readings...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartInstance = null;

    function fetchDashboardData() {
        fetch('/api/dashboard-data')
            .then(response => response.json())
            .then(data => {
                // Update stats
                document.getElementById('current-level').textContent = data.current_level + '%';
                document.getElementById('avg-today').textContent = data.avg_today + '%';
                document.getElementById('total-readings').textContent = data.total_readings;
                document.getElementById('critical-readings').textContent = data.critical_readings;
                document.getElementById('last-update').textContent = 'Last update: ' + data.last_update;

                // Update chart
                updateChart(data.recent_readings);

                // Update table
                updateTable(data.recent_readings);
            })
            .catch(error => {
                console.error('Error fetching dashboard data:', error);
                document.getElementById('current-level').textContent = 'Error';
            });
    }

    function updateChart(readings) {
        const labels = readings.map(r => new Date(r.created_at).toLocaleTimeString());
        const dataValues = readings.map(r => r.uv_reading);

        const ctx = document.getElementById('chartCanvas');
        
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'UV Reading (%)',
                    data: dataValues,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#f59e0b',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#f3f4f6'
                        }
                    },
                    x: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function updateTable(readings) {
        const tbody = document.getElementById('readings-tbody');
        
        if (readings.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-500">No readings available</td></tr>';
            return;
        }

        tbody.innerHTML = readings.map(reading => {
            const time = new Date(reading.created_at).toLocaleString();
            const uvLevel = reading.uv_reading;
            let levelText, badgeClass;

            if (uvLevel < 20) {
                levelText = 'Low';
                badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
            } else if (uvLevel < 50) {
                levelText = 'Moderate';
                badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
            } else if (uvLevel < 75) {
                levelText = 'High';
                badgeClass = 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
            } else {
                levelText = 'Extreme';
                badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
            }

            return `
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="py-3 px-4 text-gray-700 dark:text-gray-300">${time}</td>
                    <td class="py-3 px-4 text-gray-900 dark:text-white font-semibold">${uvLevel.toFixed(2)}%</td>
                    <td class="py-3 px-4">${levelText}</td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${badgeClass}">
                            ${uvLevel.toFixed(1)}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Initial load and refresh every 10 seconds
    fetchDashboardData();
    setInterval(fetchDashboardData, 10000);
</script>
