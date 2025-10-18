<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 mt-5">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />

            <!-- Filtros + Acciones -->
            <div
                class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 flex flex-col lg:flex-row justify-between gap-4 items-start lg:items-center">
                <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                    <x-text-input id="name" name="name" type="text" class="mt-1 w-full sm:w-48" :value="request('name')"
                        placeholder="Nombre" />
                    <x-text-input id="lastname" name="lastname" type="text" class="mt-1 w-full sm:w-48"
                        :value="request('lastname')" placeholder="Apellido" />
                    <x-text-input id="email" name="email" type="text" class="mt-1 w-full sm:w-48"
                        :value="request('email')" placeholder="Correo" />
                    <select name="is_active"
                        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 w-full md:w-auto">
                        <option value="">-- Estado --</option>
                        <option value="1" @selected(request('is_active') === '1')>Activo</option>
                        <option value="0" @selected(request('is_active') === '0')>Inactivo</option>
                        </option>
                    </select>

                    <x-secondary-button type="submit">Buscar</x-secondary-button>
                </form>

                <div class="grid grid-cols-2 gap-2 sm:gap-0 w-full lg:w-auto">
                    <a target="_blank" href="{{ route('users.export', request()->only(['name', 'lastname','email','is_active'])) }}">
                        <x-terciary-button type="button">Exportar PDF</x-terciary-button>
                    </a>
                    <a href="{{ route('users.create') }}">
                        <x-primary-button type="button">Crear Usuario</x-primary-button>
                    </a>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="text-left text-gray-700 dark:text-gray-100">
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Apellido</th>
                            <th class="px-4 py-3">Correo</th>
                            <th class="px-4 py-3">Activo</th>
                            <th class="px-4 py-3">Creado</th>
                            <th class="px-4 py-3">Actualizado</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-black dark:text-gray-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-4 py-4">{{ $user->name }}</td>
                                <td class="px-4 py-4">{{ $user->lastname }}</td>
                                <td class="px-4 py-4">{{ $user->email }}</td>
                                <td class="px-4 py-4">
                                    <form method="POST" action="{{ route('users.toggleIsActive', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1 rounded-md text-white {{ $user->is_active ? 'bg-green-600' : 'bg-red-600' }}">
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-4 fecha-entrada" data-fecha="{{ $user->created_at }}"></td>
                                @if ($user->created_at != $user->updated_at)
                                    <td class="px-4 py-4 fecha-entrada" data-fecha="{{ $user->updated_at }}"></td>
                                @else
                                    <td class="px-4 py-4 text-black dark:text-gray-100 ">No ha sido actualizado</td>
                                @endif

                                <td class="px-4 py-4 flex gap-2 flex-wrap">
                                    <a href="{{ route('users.edit', $user) }}">
                                        <x-primary-button>Editar</x-primary-button>
                                    </a>
                                    <a href="{{ route('users.show', $user) }}">
                                        <x-secondary-button>Ver</x-secondary-button>
                                    </a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                        id="delete-form-{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button type="button" onclick="confirmDelete({{ $user->id }})">
                                            Eliminar
                                        </x-danger-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.fecha-entrada').forEach((el) => {
            const fecha = el.dataset.fecha;
            el.innerText = dayjs(fecha).fromNow();
        });
    });
    function confirmDelete(userId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) document.getElementById('delete-form-' + userId).submit();
        });
    }
</script>
