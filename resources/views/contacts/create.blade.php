<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Crear Contacto') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <x-auth-session-status class="mb-4" :status="session('success')" />
        <x-auth-session-error class="mb-4" :status="session('error')" />
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('contacts.store') }}">
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Apellido -->
                    <div class="mb-4">
                        <x-input-label for="lastname" :value="__('LastName')" />
                        <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full"
                            :value="old('lastname')" required autofocus autocomplete="lastname" />
                        <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-4">
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                            :value="old('phone')" required autofocus autocomplete="phone" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('contacts.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2">
                            Cancelar
                        </a>
                        <x-terciary-button>
                            Crear
                        </x-terciary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
