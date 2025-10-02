<x-app-layout> <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100"> {{ __('Auditoria') }} </h2>
    </x-slot>
    <div class="py-6 ">
        <div
            class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-5 text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-800">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6"> <!-- Filtros -->
                <form method="GET" class="mb-4 flex gap-2 mt-6">
                    <div class="mb-4"> <x-input-label for="description" value="Descripción" /> <x-text-input type="text"
                            name="description" placeholder="Buscar por descripción" value="{{ request('description') }}"
                            class="border p-2 rounded" /> </div>
                    <div class="mb-4"> <x-input-label for="from" value="Desde" /> <x-text-input type="date" name="from"
                            value="{{ request('from') }}" class="border p-2 rounded" /> </div>
                    <div class="mb-4"> <x-input-label for="to" value="Hasta" /> <x-text-input type=" date" name="to"
                            value="{{ request('to') }}" class="border p-2 rounded" /> </div>
                    <div class="mt-5"> <x-primary-button>Filtrar</x-primary-button> </div>
                    <a target="_blank" class="ml-auto mt-5"
                        href="{{ route('audits.export', request()->only(['description', 'from', 'to'])) }}">
                        <x-terciary-button type="button">
                            Exportar PDF
                        </x-terciary-button>
                    </a>
                </form>

                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                        <thead class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                            <tr>
                                <th class=" px-3 py-2">Fecha</th>
                                <th class=" px-3 py-2">Acción</th>
                                <th class=" px-3 py-2">Entidad</th>
                                <th class=" px-3 py-2">Id</th>
                                <th class=" px-3 py-2">Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                            @foreach($logs as $log) <tr>
                                <td class=" px-3 py-2">{{ $log->created_at }}</td>
                                <td class=" px-3 py-2">{{ $log->description }}</td>
                                <td class=" px-3 py-2"> {{ class_basename($log->subject_type) ?? '-' }} </td>
                                <td class=" px-3 py-2">{{ $log->subject_id }}</td>
                                <td class=" px-3 py-2"> @if($log->properties)
                                    <pre
                                        class="text-xs">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @else - @endif
                                </td>
                            </tr> @endforeach </tbody>
                    </table>
                    <div class="mt-4"> {{ $logs->links() }} </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
