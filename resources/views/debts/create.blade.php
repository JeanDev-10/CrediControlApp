<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nueva Deuda') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('debts.store') }}">
                    @csrf

                    <!-- Descripción -->
                    <div class="mb-4">
                        <x-input-label for="description" value="Descripción" />
                        <x-text-input id="description" name="description" type="text" class="w-full mt-1"
                            value="{{ old('description') }}" required autofocus />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Cantidad -->
                    <div class="mb-4">
                        <x-input-label for="quantity" value="Cantidad" />
                        <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="w-full mt-1"
                            value="{{ old('quantity') }}" required />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    <!-- Fecha de inicio -->
                    <div class="mb-4">
                        <x-input-label for="date_start" value="Fecha de inicio" />
                        <x-text-input id="date_start" name="date_start" type="date" class="w-full mt-1"
                            value="{{ old('date_start') }}" required />
                        <x-input-error :messages="$errors->get('date_start')" class="mt-2" />
                    </div>
                   <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                    <!-- Contacto con buscador -->
                    <div class="mb-4">
                        <x-input-label for="contact_id" value="Contacto" />
                        <select id="contact_id" name="contact_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                            <option value="">-- Seleccionar contacto --</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}" @selected(old('contact_id') == $contact->id)>
                                    {{ $contact->name }} {{ $contact->lastname }} - {{ $contact->phone }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('contact_id')" class="mt-2" />
                        <small class="text-gray-500 dark:text-gray-400">Escribe para filtrar rápidamente.</small>
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-secondary-button onclick="history.back()">Cancelar</x-secondary-button>
                        <x-primary-button class="ml-3">Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script simple para buscador rápido en el select --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('contact_id');
            select.addEventListener('keyup', function (e) {
                const search = e.target.value.toLowerCase();
                Array.from(select.options).forEach(option => {
                    option.style.display = option.text.toLowerCase().includes(search) ? '' : 'none';
                });
            });
        });
    </script>
</x-app-layout>
