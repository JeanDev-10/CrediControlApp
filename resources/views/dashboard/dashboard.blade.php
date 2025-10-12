<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 px-2 sm:px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include("dashboard.partials.transactionsChart", ['chartData' => $chartData])
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include("dashboard.partials.topDebtorsChart", ['data' => $data])
            </div>
        </div>
    </div>
</x-app-layout>
