<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 px-2 sm:px-4 lg:px-8">
        {{-- === CARDS === --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {{-- Total de contactos --}}
            <x-card-stat title="Contactos Registrados" :value="$stats['total_contacts']" color="text-blue-600">
                <x-slot name="icon">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m13-4a4 4 0 11-8 0 4 4 0 018 0zM7 8a4 4 0 108 0 4 4 0 00-8 0z" />
                    </svg>
                </x-slot>
            </x-card-stat>

            {{-- Total de deudas pendientes --}}
            <x-card-stat title="Deudas Pendientes" :value="$stats['pending_debts_count']" color="text-yellow-600">
                <x-slot name="icon">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </x-slot>
            </x-card-stat>

            {{-- Total dinero pendiente --}}
            <x-card-stat class="col-span-2 md:col-span-1" title="Monto Total Pendiente" :value="'$' . number_format($stats['pending_debts_total'], 2)" color="text-green-400" >
                <x-slot name="icon">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3 0-6 2-6 6 0 3 2.5 6 6 6s6-3 6-6c0-4-3-6-6-6zm0-6v2m0 16v2m4.24-13.76L16 8m-8 0l-.24-.76M4 14h16" />
                    </svg>
                </x-slot>
            </x-card-stat>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 max-w-7xl mx-auto mt-5">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include("dashboard.partials.transactionsChart", ['chartData' => $stats['transactions_data']])
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @include("dashboard.partials.topDebtorsChart", ['data' => $stats['debts_data']])
            </div>
        </div>
    </div>
</x-app-layout>
