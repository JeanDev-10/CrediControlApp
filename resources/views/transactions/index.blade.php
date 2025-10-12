<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transacciones') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-auth-session-status class="mb-4" :status="session('success')" />

            {{-- Filtros --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap gap-4 items-center">
                    <x-text-input name="description" placeholder="Compra de comida" value="{{ request('description') }}" class="w-full md:w-auto" />
                    <x-text-input name="date" type="date" value="{{ request('date') }}" class="w-full md:w-auto" />
                    <select name="type" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 w-full md:w-auto">
                        <option value="">-- Tipo --</option>
                        <option value="ingreso" @selected(request('type') === 'ingreso')>Ingreso</option>
                        <option value="egreso" @selected(request('type') === 'egreso')>Gasto</option>
                        <option value="actualizacion" @selected(request('type') === 'actualizacion')>Actualización</option>
                    </select>
                    <x-primary-button>{{ __('Filtrar') }}</x-primary-button>
                </form>
                @include('transactions.partials.budget-user', ['budget' => $budget])
            </div>

            {{-- Lista --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4 flex-col sm:flex-row">
                    <form method="POST" action="{{ route('budget.setup') }}" class="flex gap-2 flex-col sm:flex-row">
                        @csrf
                        <div class="flex gap-2">
                            <x-text-input name="quantity" type="number" step="0.01" placeholder="Configurar presupuesto" :value="old('quantity')" class="w-full sm:w-auto" />
                            <x-secondary-button type="submit">{{ __('Guardar') }}</x-secondary-button>
                        </div>
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </form>

                    <div class="flex gap-2 mt-4 sm:mt-0">
                        <a target="_blank" href="{{ route('transactions.export', request()->only(['description','date','type'])) }}">
                            <x-terciary-button type="button">Exportar PDF</x-terciary-button>
                        </a>
                        <a href="{{ route('transactions.create') }}" class="ml-2">
                            <x-primary-button>{{ __('Nueva Transacción') }}</x-primary-button>
                        </a>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                                <th class="p-2">Descripción</th>
                                <th class="p-2">Tipo</th>
                                <th class="p-2">Cantidad</th>
                                <th class="p-2">Saldo anterior</th>
                                <th class="p-2">Saldo actual</th>
                                <th class="p-2">Fecha</th>
                                <th class="p-2 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr class="border-b border-gray-200 dark:border-gray-700 text-black dark:text-gray-100">
                                    <td class="p-2">{{ $transaction->description }}</td>
                                    <td class="p-2">
                                        @php
                                            switch ($transaction->type) {
                                                case 'Ingreso':
                                                    $color = 'text-green-600 dark:text-green-400';
                                                    break;
                                                case 'Actualizacion':
                                                    $color = 'text-blue-600 dark:text-blue-400';
                                                    break;
                                                default:
                                                    $color = 'text-red-600 dark:text-red-400';
                                            }
                                        @endphp
                                        <span class="{{ $color }}">{{ $transaction->type }}</span>
                                    </td>
                                    <td class="p-2 {{ $color }}">${{ $transaction->quantity }}</td>
                                    @php
                                        $color = $transaction->previus_quantity >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                                    @endphp
                                    <td class="p-2 {{ $color }}">${{ $transaction->previus_quantity }}</td>
                                    <td class="p-2 {{ $color }}">${{ $transaction->after_quantity }}</td>
                                    <td class="p-2">{{ $transaction->created_at }}</td>
                                    <td class="p-2 text-right">
                                        <div class="flex justify-end gap-2">
                                            @can('update', $transaction)
                                                <a href="{{ route('transactions.edit', $transaction) }}">
                                                    <x-primary-button>{{ __('Editar') }}</x-primary-button>
                                                </a>
                                            @endcan
                                            @can('delete', $transaction)
                                                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button onclick="return confirm('¿Eliminar transacción?')">{{ __('Eliminar') }}</x-danger-button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay transacciones.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
