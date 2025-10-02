<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pagos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />

            {{-- Filtros --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form method="GET" action="{{ route('pays.index') }}" class="flex flex-wrap gap-3 items-center">
                    <div>
                        <x-input-label for="contact_name" value="Nombre de contacto" />
                        <x-text-input id="contact_name" name="contact_name" type="text" class="w-full mt-1"
                            placeholder="Jean Rodriguez" :value="request('contact_name')" />
                    </div>
                    <div>
                        <x-input-label for="quantity" value="Cantidad" />
                        <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="w-full mt-1"
                            placeholder="10" :value="request('quantity')" />
                    </div>
                    <div>
                        <x-input-label for="date" value="Fecha" />
                        <x-text-input id="date" name="date" type="date" class="w-full mt-1" :value="request('date')" />
                    </div>
                    <x-secondary-button type="submit" class="mt-4">Filtrar</x-secondary-button>
                    <a target="_blank" class="ml-auto mt-4"
                        href="{{ route('pays.export', request()->only(['contact_name', 'quantity', 'date'])) }}">
                        <x-terciary-button type="button">
                            Exportar PDF
                        </x-terciary-button>
                    </a>
                    <a href="{{ route('pays.create') }}" class="mt-4">
                        <x-primary-button type="button">{{ __('Nuevo Pago') }}</x-primary-button>
                    </a>
                </form>
            </div>

            {{-- Tabla de pagos --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                    <thead class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-3">Contacto</th>
                            <th class="px-4 py-3 text-center">Cantidad</th>
                            <th class="px-4 py-3 text-center">Descripción deuda</th>
                            <th class="px-4 py-3 text-center">Fecha</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                        @forelse($pays as $pay)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('contacts.show', $pay->debt->contact) }}"
                                        class="text-blue-700 hover:text-blue-600 dark:text-blue-300 hover:text-blue-200">
                                        {{ $pay->debt->contact->name ?? '-' }} {{ $pay->debt->contact->lastname ?? '' }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center text-green-600 dark:text-green-400">${{ $pay->quantity }}
                                </td>
                                <td class="px-4 py-3 text-center">

                                    <a href="{{ route('debts.show', $pay->debt) }}"
                                        class="text-blue-700 hover:text-blue-600 dark:text-blue-300 hover:text-blue-200">
                                        {{ $pay->debt->description }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $pay->date->format("Y/m/d") }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold {{ $pay->debt->status === 'pendiente' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                                        {{ ucfirst($pay->debt->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        @can('update', $pay)
                                            <a href="{{ route('pays.edit', $pay) }}">
                                                <x-terciary-button>Editar</x-terciary-button>
                                            </a>
                                        @endcan
                                        <a href="{{ route('pays.show', $pay) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-sm">Ver</a>
                                        @can('delete', $pay)
                                            <form action="{{ route('pays.destroy', $pay) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button
                                                    onclick="return confirm('Eliminar pago?')">Eliminar</x-danger-button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay pagos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $pays->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
