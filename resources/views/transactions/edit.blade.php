<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            {{ __('Editar Transacción') }}
        </h2>
    </x-slot>
    <div class="mt-5 w-1/3 text-center m-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        @include("transactions.partials.budget-user", ['budget' => $budget])
    </div>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
                <x-auth-session-error class="mb-4" :status="session('error')" />
                <x-auth-session-status class="mb-4" :status="session('success')" />
                <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Descripción --}}
                    <div>
                        <x-input-label for="description" value="Descripción" />
                        <x-text-input id="description" name="description" type="text" class="w-full mt-1"
                            value="{{ old('description', $transaction->description) }}"
                            placeholder="Ej: Pago de servicios" required />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <x-input-label for="type" value="Tipo de transacción" />
                        <select id="type" name="type"
                            class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                            <option value="ingreso" @selected($transaction->type === 'Ingreso')>Ingreso</option>
                            <option value="egreso" @selected($transaction->type === 'Egreso')>Gasto</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    {{-- Cantidad --}}
                    <div>
                        <x-input-label for="quantity" value="Cantidad" />
                        <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="w-full mt-1"
                            value="{{ old('quantity', $transaction->quantity) }}" required />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('transactions.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2">
                            Cancelar
                        </a>
                        <x-terciary-button>
                            Actualizar
                        </x-terciary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
