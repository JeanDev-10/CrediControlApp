<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Deudas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />

            {{-- Filtros --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form method="GET" action="{{ route('debts.index') }}" class="flex flex-wrap gap-3 items-center">
                    <div>
                        <x-input-label for="description" value="Descripción" />
                        <x-text-input id="description" name="description" type="text" class="w-full mt-1"
                            placeholder="Pago de servicios" :value="request('description')" />
                    </div>
                    <div>
                        <x-input-label for="contact_name" value="Nombre de contacto" />
                        <x-text-input id="contact_name" name="contact_name" type="text" class="w-full mt-1"
                            :value="request('contact_name')" placeholder="Jean Rodriguez" />
                    </div>
                    <div>
                        <x-input-label for="date_start" value="Fecha de inicio" />
                        <x-text-input id="date_start" name="date_start" type="date" class="w-full mt-1"
                            :value="request('date_start')" />
                    </div>
                    <div>
                        <x-input-label for="status" value="Estado" />
                        <select name="status" :value="request('status')"
                            class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                            <option value="">-- Estado --</option>
                            <option value="pendiente" @selected(request('status') == 'pendiente')>Pendiente</option>
                            <option value="pagada" @selected(request('status') == 'pagada')>Pagada</option>
                        </select>
                    </div>
                    <x-secondary-button type="submit" class="mt-4">Filtrar</x-secondary-button>
                    <a href="{{ route('debts.create') }}" class="ml-auto mt-4">
                        <x-primary-button type="button">{{ __('Nueva Deuda') }}</x-primary-button>
                    </a>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                    <thead class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Contacto</th>
                            <th class="px-4 py-3 text-center">Cantidad</th>
                            <th class="px-4 py-3 text-center">Fecha Inicio</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                        @forelse($debts as $debt)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('debts.show', $debt) }}"
                                        class="text-blue-700 hover:text-blue-600 dark:text-blue-300 hover:text-blue-200">
                                        {{ $debt->description }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('contacts.show', $debt->contact) }}"
                                        class="text-blue-700 hover:text-blue-600 dark:text-blue-300 hover:text-blue-200">
                                        {{ $debt->contact->name ?? '-' }} {{ $debt->contact->lastname ?? '' }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center">${{ $debt->quantity }}</td>
                                <td class="px-4 py-3 text-center">{{ $debt->date_start->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold {{ $debt->status === 'pendiente' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                                        {{ ucfirst($debt->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        @can('update', $debt)
                                            <a href="{{ route('debts.edit', $debt) }}">
                                                <x-terciary-button>Editar</x-terciary-button>
                                            </a>
                                        @endcan
                                        <a href="{{ route('debts.show', $debt) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-sm">Ver</a>
                                        @can('delete', $debt)
                                            <form action="{{ route('debts.destroy', $debt) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button
                                                    onclick="return confirm('Eliminar deuda?')">Eliminar</x-danger-button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay
                                    deudas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $debts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
