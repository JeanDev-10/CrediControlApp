<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Auditoria') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 mt-5">

            <!-- Filtros + Acciones -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">

                <!-- Formulario de búsqueda -->
                <form method="GET" class="flex flex-wrap gap-3 items-center justify-between">
                    <div class="flex flex-wrap gap-3 items-center justify-between">
                        <div class="w-full sm:w-auto">
                            <x-input-label for="description" value="Descripción" />
                            <x-text-input type="text" name="description" placeholder="Buscar por descripción"
                                value="{{ request('description') }}" class="border p-2 rounded w-full" />
                        </div>
                        <div class="w-full sm:w-auto">
                            <x-input-label for="from" value="Desde" />
                            <x-text-input type="date" name="from" value="{{ request('from') }}"
                                class="border p-2 rounded w-full" />
                        </div>
                        <div class="w-full sm:w-auto">
                            <x-input-label for="to" value="Hasta" />
                            <x-text-input type="date" name="to" value="{{ request('to') }}"
                                class="border p-2 rounded w-full" />
                        </div>
                        <div class="w-full sm:w-auto">
                            <x-input-label for="user_id" value="Usuario (Client)" />
                            <select id="user_id" name="user_id"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">-- Seleccionar usuario --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? null) == $user->id)>
                                        {{ $user->name }} {{ $user->lastname }}-{{ $user->email }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="w-full my-3 sm:w-auto sm:mt-5">
                            <x-secondary-button type="submit"
                                class="sm:mt-2 w-full sm:w-auto">Filtrar</x-secondary-button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 m-auto sm:m-0">
                        <a target="_blank"
                            href="{{ route('audits.export', request()->only(['description', 'from', 'to','user_id'])) }}"
                            variant="tertiary" class="sm:mt-2 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Exportar
                            PDF
                        </a>

                    </div>

                </form>
            </div>

            <!-- Tabla de Auditoría -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg sm:p-6 mt-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                    <thead class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Acción</th>
                            <th class="px-4 py-3 text-left">Entidad</th>
                            <th class="px-4 py-3 text-left">Id</th>
                            <th class="px-4 py-3 text-left">Usuario</th>
                            <th class="px-4 py-3 text-left">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-black dark:text-gray-100">
                        @foreach($logs as $log)
                            <tr>
                                <td class="px-4 py-3 fecha-entrada" data-fecha="{{ $log->created_at }}"></td>
                                <td class="px-4 py-3">{{ $log->description }}</td>
                                <td class="px-4 py-3">{{ class_basename($log->subject_type) ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $log->subject_id }}</td>
                                <td class="px-4 py-3">
                                    {{ optional($log->causer)->name }} {{ optional($log->causer)->lastname }}
                                </td>

                                <td class="px-4 py-3">
                                    @if($log->properties)
                                        <pre
                                            class="text-xs">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="p-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Seleccionamos todas las celdas que tienen las fechas
        const fechaElements = document.querySelectorAll('.fecha-entrada');
        const select = document.getElementById('user_id');

        // Recorremos cada celda y mostramos la fecha relativa
        fechaElements.forEach((element) => {
            const fecha = element.getAttribute('data-fecha');
            element.innerText = dayjs(fecha).fromNow();
        });

        select.addEventListener('keyup', function (e) {
            const search = e.target.value.toLowerCase(); Array.from(select.options).forEach(option => { option.style.display = option.text.toLowerCase().includes(search) ? '' : 'none'; });
        }
        );
    });
</script>
