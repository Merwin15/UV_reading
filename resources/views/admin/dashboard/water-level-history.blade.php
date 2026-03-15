<div class="p-10">
    <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
        <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C12 2 7 8.5 7 13a5 5 0 0010 0c0-4.5-5-10-5-10z" />
        </svg>
        UV Reading History
    </h2>
    <div class="bg-white rounded-2xl shadow-xl p-10">
        @if($history && count($history))
            <div class="chart-container">
                <canvas id="uvReadingChart"></canvas>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('uvReadingChart').getContext('2d');
                const labels = @json($history->pluck('created_at')->map(fn($dt) => $dt->format('H:i')));
                const values = @json($history->pluck('uv_reading'));
                const meta = @json($history->map(fn($r) => [
                    'time' => $r->created_at->format('Y-m-d H:i:s'),
                    'level' => $r->uv_reading,
                    'ip' => $r->ip_address
                ]));
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'UV Reading (%)',
                            data: values,
                            borderColor: '#166534',
                            backgroundColor: 'rgba(22, 101, 52, 0.10)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 7
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
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        const idx = context.dataIndex;
                                        const m = meta[idx];
                                        const level = m.level;
                                        let category = 'Safe';
                                        if (level >= 25 && level <= 60) {
                                            category = 'Moderate';
                                        } else if (level > 60) {
                                            category = 'Danger';
                                        }
                                        const heat = Math.round(level);
                                        return `UV: ${level}% (${category}) | Heat: ${heat}% | Time: ${m.time} | IP: ${m.ip}`;
                                    }
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
            <p class="text-green-700">No UV reading history data available.</p>
        @endif
    </div>
</div>
</div>
