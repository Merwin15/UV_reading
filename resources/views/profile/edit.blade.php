
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-indigo-600 dark:text-indigo-300 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-100 via-purple-100 to-indigo-200 dark:from-gray-900 dark:via-indigo-900 dark:to-purple-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8 flex flex-col items-center">
                <div class="w-full max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8 flex flex-col items-center">
                <div class="w-full max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-8 flex flex-col items-center">
                <div class="w-full max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
