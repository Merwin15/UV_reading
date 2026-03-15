

<x-app-layout>
    <div class="flex min-h-screen">
        @include('components.sidebar')
        <main class="flex-1 bg-gradient-to-br from-indigo-100 via-purple-100 to-indigo-200 dark:from-gray-900 dark:via-indigo-900 dark:to-purple-900">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
                @if(request()->routeIs('dashboard'))
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
                        <div class="p-8 text-gray-900 dark:text-gray-100 text-center">
                            {{ __("You're logged in!") }}
                        </div>
                    </div>
                @elseif(request()->routeIs('uv-history'))
                    @include('dashboard.uv-history')
                @elseif(request()->routeIs('recent-readings'))
                    @include('dashboard.recent-readings')
                @endif
            </div>
        </main>
    </div>
</x-app-layout>
