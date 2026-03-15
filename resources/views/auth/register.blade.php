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

                <!-- Right Side: Signup Form -->
                <div class="w-full md:w-2/3 p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1">Get started</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Create your account today</p>
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

                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 dark:text-gray-300 text-xs font-semibold block mb-2 uppercase tracking-wide" />
                            <x-text-input
                                id="name"
                                class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-sm rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition duration-200"
                                type="text"
                                name="name"
                                :value="old('name')"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="John Doe"
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 text-xs font-semibold block mb-2 uppercase tracking-wide" />
                            <x-text-input
                                id="email"
                                class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-sm rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition duration-200"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
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
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 dark:text-gray-300 text-xs font-semibold block mb-2 uppercase tracking-wide" />
                            <x-text-input
                                id="password_confirmation"
                                class="block w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-sm rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition duration-200"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg transition duration-200 text-sm mt-4 shadow-sm hover:shadow-md">
                            Sign up
                        </button>
                        <p class="text-center text-xs text-gray-600 dark:text-gray-400 mt-4">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold transition duration-200">Sign in</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>