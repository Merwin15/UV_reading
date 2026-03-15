<div class="p-8">
    <h2 class="text-2xl font-semibold text-indigo-600 dark:text-indigo-300 mb-4"></h2>
    <div class="p-10">
        <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
            <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C12 2 7 8.5 7 13a5 5 0 0010 0c0-4.5-5-10-5-10z" />
            </svg>
            Recent Readings
        </h2>
        <div class="bg-white rounded-2xl shadow-xl p-10">
            @if($readings && count($readings))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="flex flex-col items-center rounded-xl border border-green-100 bg-green-50 p-4">
                        <span class="text-xs font-semibold text-green-700 mb-1">Safe (UV &lt; 25%)</span>
                        <span class="text-2xl font-bold text-green-700">{{ $safeCount ?? 0 }}</span>
                    </div>
                    <div class="flex flex-col items-center rounded-xl border border-yellow-100 bg-yellow-50 p-4">
                        <span class="text-xs font-semibold text-yellow-700 mb-1">Moderate (25–60%)</span>
                        <span class="text-2xl font-bold text-yellow-700">{{ $moderateCount ?? 0 }}</span>
                    </div>
                    <div class="flex flex-col items-center rounded-xl border border-red-100 bg-red-50 p-4">
                        <span class="text-xs font-semibold text-red-700 mb-1">Danger (&gt; 60%)</span>
                        <span class="text-2xl font-bold text-red-700">{{ $dangerCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="recentReadingsBarChart"></canvas>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                const ctx = document.getElementById('recentReadingsBarChart').getContext('2d');
                const labels = @json($readings->take(10)->pluck('created_at')->map(fn($dt) => $dt->format('H:i')));
                const values = @json($readings->take(10)->pluck('uv_reading'));
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                        datasets: [{
                            label: 'UV Reading (%)',
                                data: values,
                                backgroundColor: '#22c55e',
                                borderColor: '#166534',
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#166534',
                                        font: { size: 14, weight: 'bold' }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.parsed.y;
                                            let category = 'Safe';
                                            if (value >= 25 && value <= 60) {
                                                category = 'Moderate';
                                            } else if (value > 60) {
                                                category = 'Danger';
                                            }
                                            const heat = Math.round(value);
                                            return `UV: ${value}% (${category}) | Heat: ${heat}%`;
                                        }
                                    }
                                },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        color: '#166534',
                                        callback: value => value + '%'
                                    },
                                    grid: {
                                        color: '#e5e7eb'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#166534',
                                        maxRotation: 0,
                                        minRotation: 0
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                </script>
            @else
                <p class="text-green-700">No recent readings available.</p>
            @endif
        </div>
    </div>
</div>
