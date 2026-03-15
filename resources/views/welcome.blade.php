<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true',
    }"
    x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))"
    :class="{ 'dark': darkMode }"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>UV Monitoring System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="flex items-center justify-between px-6 py-4 md:px-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="4" fill="currentColor" />
                        <path stroke-linecap="round" d="M12 2v2.5M12 19.5V22M4.22 4.22l1.77 1.77M17.99 17.99l1.79 1.79M2 12h2.5M19.5 12H22M4.22 19.78l1.77-1.77M17.99 6.01l1.79-1.79" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-green-700 dark:text-green-300">UV Monitoring</span>
            </div>

            <div class="flex items-center gap-3">
                <!-- Dark mode toggle -->
                <button
                    type="button"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-600 text-yellow-500 dark:text-yellow-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none"
                    @click="darkMode = !darkMode"
                >
                    <template x-if="!darkMode">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="4" />
                            <path stroke-linecap="round" d="M12 2v2.5M12 19.5V22M4.22 4.22l1.77 1.77M17.99 17.99l1.79 1.79M2 12h2.5M19.5 12H22M4.22 19.78l1.77-1.77M17.99 6.01l1.79-1.79" />
                        </svg>
                    </template>
                    <template x-if="darkMode">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                        </svg>
                    </template>
                </button>

                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-gray-900 dark:text-gray-100 text-sm font-semibold hover:text-green-600 transition duration-200">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-gray-900 dark:text-gray-100 text-sm font-semibold hover:text-green-600 transition duration-200">
                        Sign in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition duration-200 shadow-sm hover:shadow-md">
                            Get started
                        </a>
                    @endif
                @endauth
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 md:py-20">
            <div class="max-w-2xl w-full text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-6 leading-tight">
                    Monitor UV Levels with Precision
                </h1>

                <p class="text-lg text-gray-600 dark:text-gray-300 mb-10 leading-relaxed">
                    Protect your crops from harmful UV radiation. Get real-time alerts, detailed analytics, and actionable insights to optimize your farming operations.
                </p>

                <div class="flex flex-col md:flex-row gap-4 justify-center mb-16">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-200 shadow-sm hover:shadow-md">
                            Start Monitoring
                        </a>
                    @endif
                    <a href="#features" class="px-8 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 font-semibold rounded-xl hover:border-gray-300 dark:hover:border-gray-500 transition duration-200">
                        Learn More
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="bg-white dark:bg-gray-900 py-16 md:py-24 px-6 md:px-8 border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        Powerful Features for Farmers
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300">
                        Everything you need to protect our dear farmers.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="p-8 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-500 transition duration-200">
                        <div class="w-12 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                            <span class="text-2xl">🚨</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Instant Alerts</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Receive real-time notifications when UV levels exceed safe thresholds for your crops.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-500 transition duration-200">
                        <div class="w-12 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                            <span class="text-2xl">🚨</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Instant Alerts</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Receive real-time notifications when UV levels exceed safe thresholds for your crops.
                        </p>
                    </div>
                    <div class="p-8 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-500 transition duration-200">
                        <div class="w-12 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                            <span class="text-2xl">🚨</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Instant Alerts</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Receive real-time notifications when UV levels exceed safe thresholds for your crops.
                        </p>
                    </div>