

<x-app-layout>
    <div class="flex min-h-screen bg-gradient-to-br from-green-100 to-white">
        @include('components.sidebar')
        <main class="flex-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
                @if(request()->routeIs('admin.dashboard'))
                    <div class="mb-8 rounded-2xl bg-green-700 p-10 flex flex-col gap-4 shadow-xl">
                        <h3 class="text-4xl font-extrabold text-white tracking-tight">Welcome, {{ Auth::user()->name }} 👋</h3>
                        <span class="text-white text-lg">Monitor UV readings in real-time with professional insights.</span>
                    </div>
                    @if(session('status'))
                        <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="mb-6 flex justify-end">
                        <form method="POST" action="{{ route('admin.sensor-readings.clear') }}" onsubmit="return confirm('Are you sure you want to wipe all sensor data? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700 shadow-sm">
                                Clear All Sensor Data
                            </button>
                        </form>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                        <!-- Average Heat Today -->
                        <div class="bg-white border-t-4 border-green-700 rounded-2xl p-8 flex flex-col items-center shadow-lg">
                            <div class="bg-green-100 p-4 rounded-full mb-3">
                                <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17v-2a4 4 0 014-4h10a4 4 0 014 4v2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 01-8 0" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-700 mb-1">Average Heat Today</span>
                            <span class="text-4xl font-bold text-green-700 mb-1">
                                {{ $avgToday ? number_format($avgToday, 2) : '--' }}°
                            </span>
                            <span class="text-xs text-green-700">Based on all readings today</span>
                        </div>
                        <!-- Average UV Index Today -->
                        <div class="bg-white border-t-4 border-green-700 rounded-2xl p-8 flex flex-col items-center shadow-lg">
                            <div class="bg-green-100 p-4 rounded-full mb-3">
                                <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C12 2 7 8.5 7 13a5 5 0 0010 0c0-4.5-5-10-5-10z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-700 mb-1">Average UV Index Today</span>
                            <span class="text-4xl font-bold text-green-700 mb-1">
                                {{ isset($avgUvIndex) && $avgUvIndex !== null ? $avgUvIndex : '--' }}
                            </span>
                            <span class="text-xs text-green-700">Scale: 0 (Low) – 11+ (Extreme)</span>
                        </div>
                        <div class="bg-white border-t-4 border-green-700 rounded-2xl p-8 flex flex-col items-center shadow-lg">
                            <div class="bg-green-100 p-4 rounded-full mb-3">
                                <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5h6M9 3h6a2 2 0 012 2v14a2 2 0 01-2 2H9a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6M9 13h6M9 17h6" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-700 mb-1">Total Readings</span>
                            <span class="text-4xl font-bold text-green-700 mb-1">{{ $total }}</span>
                        </div>
                        <div class="bg-white border-t-4 border-green-700 rounded-2xl p-8 flex flex-col items-center shadow-lg">
                            <div class="bg-green-100 p-4 rounded-full mb-3">
                                <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-700 mb-1">Critical Alerts</span>
                            <span class="text-4xl font-bold text-green-700 mb-1">{{ $critical }}</span>
                        </div>
                    </div>
                @elseif(request()->routeIs('admin.uv-history'))
                    @include('admin.dashboard.water-level-history')
                @elseif(request()->routeIs('admin.recent-readings'))
                    @include('admin.dashboard.recent-readings')
                @elseif(request()->routeIs('admin.live-reading'))
                    @include('admin.dashboard.live-reading')
                @elseif(request()->routeIs('admin.data-log'))
                    @include('admin.dashboard.data-log')
                @endif
            </div>
        </main>
    </div>
</x-app-layout>