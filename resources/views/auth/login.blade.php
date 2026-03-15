<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Left Side: Logo -->
                <div class="hidden md:flex md:w-1/3 items-center justify-center bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 py-8">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 flex items-center justify-center shadow-md">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="4" fill="currentColor" />
                            <path stroke-linecap="round" d="M12 2v2.5M12 19.5V22M4.22 4.22l1.77 1.77M17.99 17.99l1.79 1.79M2 12h2.5M19.5 12H22M4.22 19.78l1.77-1.77M17.99 6.01l1.79-1.79" />
                        </svg>
                    </div>
                </div>

                <!-- Right Side: Login Form -->
                <div class="w-full md:w-2/3 p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1">Welcome back</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Sign in to continue</p>
                        </div>
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
                    </div>

                    <x-auth-session-status class="mb-2" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-3">
                        @csrf
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 text-xs font-semibold block mb-2 uppercase tracking-wide" />
                            <x-text-input
                                id="email"
                                class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-sm rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition duration-200"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="you@example.com"
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                        </div>
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300 text-xs font-semibold block mb-2 uppercase tracking-wide" />
                            <x-text-input
                                id="password"
                                class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-sm rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition duration-200"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <label for="remember_me" class="flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" class="w-3.5 h-3.5 rounded border-gray-300 text-green-600 focus:ring-green-600" name="remember">
                                <span class="ms-2 text-xs text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-green-600 hover:text-green-700 font-semibold transition duration-200" href="{{ route('password.request') }}">
                                    {{ __('Forgot?') }}
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition duration-200 text-sm mt-4 shadow-sm hover:shadow-md">
                            Sign in
                        </button>
                        <p class="text-center text-xs text-gray-600 dark:text-gray-400 mt-4">
                            Don’t have an account?
                            <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 font-semibold transition duration-200">Sign up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>