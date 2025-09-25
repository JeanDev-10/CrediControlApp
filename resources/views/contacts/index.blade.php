<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Contactos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />

            <div class="mb-4">

                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="flex justify-between items-center m-4">
                        <form method="GET" action="{{ route('contacts.index') }}" class="inline">
                            <x-primary-button>Crear Contacto</x-primary-button>
                        </form>
                        <form method="GET" class="flex gap-2">
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-50"
                                    :value="request('name')" autofocus autocomplete="name" placeholder="Nombre" />
                                <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-50"
                                    :value="request('lastname')" autofocus autocomplete="lastname" placeholder="Apellido" />
                                <x-secondary-button type="submit">Buscar</x-secondary-button>
                        </form>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200  dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-black dark:text-gray-100">Nombre</th>
                                <th class="px-6 py-3 text-left text-black dark:text-gray-100">Apellido</th>
                                <th class="px-6 py-3 text-left text-black dark:text-gray-100">Teléfono</th>
                                <th class="px-6 py-3 text-left text-black dark:text-gray-100">Creado</th>
                                <th class="px-6 py-3 text-left text-black dark:text-gray-100">Actualizado</th>
                                <th class="px-6 py-3 text-left text-black dark:text-gray-100">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach ($contacts as $contact)
                                <tr>
                                    <td class="px-6 py-4 text-black dark:text-gray-100">{{ $contact->name }}</td>
                                    <td class="px-6 py-4 text-black dark:text-gray-100">{{ $contact->lastname }}</td>
                                    <td class="px-6 py-4 text-black dark:text-gray-100">{{ $contact->phone }}</td>
                                    <td class="px-6 py-4 text-black dark:text-gray-100">{{ $contact->created_at }}</td>
                                    <td class="px-6 py-4 text-black dark:text-gray-100">{{ $contact->updated_at }}</td>
                                    <td class="px-6 py-4 text-black dark:text-gray-100">
                                        <a href="{{ route('contacts.edit', $contact) }}"
                                            class='inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>Editar</a>
                                        <form method="POST" action="{{ route('contacts.destroy', $contact) }}"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button onclick="return confirm('¿Seguro que deseas eliminarlo?')">
                                                {{ __('Delete') }}
                                            </x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="p-4">
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
