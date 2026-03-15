<div class="flex flex-col h-screen bg-gradient-to-br from-green-100 to-white dark:from-gray-900 dark:to-gray-950">
    <!-- Optional overlay on small screens -->
    <div
        class="fixed inset-0 bg-black/30 z-20 sm:hidden"
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        x-transition.opacity
    ></div>

    <aside
        class="h-screen w-64 bg-white dark:bg-gray-900 shadow-xl rounded-none sm:rounded-r-2xl p-8 flex flex-col justify-between transform transition-transform duration-300 ease-in-out z-30"
        x-show="sidebarOpen"
        x-cloak
        x-transition:enter="transform transition-transform duration-300 ease-out"
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transform transition-transform duration-200 ease-in"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0"
    >
        <div>
            <nav class="space-y-6">
                @if(request()->is('admin/dashboard*'))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.uv-history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        UV Reading History
                    </a>
                    <a href="{{ route('admin.recent-readings') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                        </svg>
                        Statistics
                    </a>
                    <a href="{{ route('admin.live-reading') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 4h.01M5 7h14l-1.5 9h-11z" />
                        </svg>
                        Live Reading
                    </a>
                    <a href="{{ route('admin.data-log') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M3 10h18M3 15h18" />
                        </svg>
                        Data Log
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('uv-history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        UV Reading History
                    </a>
                    <a href="{{ route('recent-readings') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-green-700 dark:text-green-200 font-semibold hover:bg-green-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                        </svg>
                        Statistics
                    </a>
                @endif
            </nav>
        </div>
        <div>
            <span class="text-xs text-green-700 dark:text-gray-400">© 2026 UV Monitoring</span>
        </div>
    </aside>
</div>