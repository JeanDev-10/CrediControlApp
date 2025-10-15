<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Editar Contacto') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <x-auth-session-status class="mb-4" :status="session('success')" />
        <x-auth-session-error class="mb-4" :status="session('error')" />
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('contacts.update', $contact) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                    <!-- Nombre -->
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name', $contact->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Apellido -->
                    <div class="mb-4">
                        <x-input-label for="lastname" :value="__('Name')" />
                        <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full"
                            :value="old('lastname', $contact->lastname)" required autofocus autocomplete="lastname" />
                        <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-4">
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                            :value="old('phone', $contact->phone)" required autofocus autocomplete="phone" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('contacts.index') }}" onclick="event.preventDefault(); confirmExit();"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
        function confirmExit() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Perderás los cambios no guardados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'No, quedarme',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('contacts.index') }}'; // Redirige a la lista de contactos
                }
            });
        }
</script>
