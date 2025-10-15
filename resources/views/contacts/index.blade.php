<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Contactos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 mt-5">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />

            <!-- Filtros + Acciones -->
            <div
                class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 flex flex-col lg:flex-row justify-between gap-4 items-start lg:items-center">
                <!-- Formulario de búsqueda -->
                <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                    <x-text-input id="name" name="name" type="text" class="mt-1 w-full sm:w-48" :value="request('name')"
                        placeholder="Nombre" />
                    <x-text-input id="lastname" name="lastname" type="text" class="mt-1 w-full sm:w-48"
                        :value="request('lastname')" placeholder="Apellido" />
                    <x-secondary-button type="submit">Buscar</x-secondary-button>
                </form>

                <!-- Acciones -->
                <div class="grid grid-cols-2 gap-2 sm:gap-0 w-full lg:w-auto">
                    <a target="_blank" href="{{ route('contacts.export', request()->only(['name', 'lastname'])) }}">
                        <x-terciary-button type="button">Exportar PDF</x-terciary-button>
                    </a>
                    <a href="{{ route('contacts.create') }}">
                        <x-primary-button type="button">Crear Contacto</x-primary-button>
                    </a>
                </div>
            </div>

            <!-- Tabla de contactos -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-black dark:text-gray-100">Nombre</th>
                            <th class="px-4 py-3 text-left text-black dark:text-gray-100">Apellido</th>
                            <th class="px-4 py-3 text-left text-black dark:text-gray-100">Teléfono</th>
                            <th class="px-4 py-3 text-left text-black dark:text-gray-100">Creado</th>
                            <th class="px-4 py-3 text-left text-black dark:text-gray-100">Actualizado</th>
                            <th class="px-4 py-3 text-left text-black dark:text-gray-100">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($contacts as $contact)
                            <tr>
                                <td class="px-4 py-4 text-black dark:text-gray-100">{{ $contact->name }}</td>
                                <td class="px-4 py-4 text-black dark:text-gray-100">{{ $contact->lastname }}</td>
                                <td class="px-4 py-4 text-black dark:text-gray-100">{{ $contact->phone }}</td>
                                <td class="px-4 py-4 text-black dark:text-gray-100 fecha-entrada"
                                    data-fecha="{{ $contact->created_at }}"></td>
                                @if ($contact->created_at != $contact->updated_at)
                                    <td class="px-4 py-4 text-black dark:text-gray-100 fecha-entrada"
                                        data-fecha="{{ $contact->updated_at }}"></td>
                                @else
                                    <td class="px-4 py-4 text-black dark:text-gray-100 ">No ha sido actualizado</td>
                                @endif
                                <td
                                    class="px-4 py-4 text-black dark:text-gray-100 space-y-1 sm:space-y-0 sm:space-x-1 flex flex-col sm:flex-row flex-wrap">
                                    <a href="{{ route('contacts.edit', $contact) }}">
                                        <x-primary-button type="button">Editar</x-primary-button>
                                    </a>
                                    <a href="{{ route('contacts.show', $contact) }}">
                                        <x-secondary-button>Ver</x-secondary-button>
                                    </a>
                                    <form method="POST" action="{{ route('contacts.destroy', $contact) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button onclick="return confirm('¿Seguro que deseas eliminarlo?')">
                                            Eliminar
                                        </x-danger-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay
                                    contactos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="p-4">
                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Seleccionamos todas las celdas que tienen las fechas
        const fechaElements = document.querySelectorAll('.fecha-entrada');

        // Recorremos cada celda y mostramos la fecha relativa
        fechaElements.forEach((element) => {
            const fecha = element.getAttribute('data-fecha');
            element.innerText = dayjs(fecha).fromNow();
        });
    });
</script>
