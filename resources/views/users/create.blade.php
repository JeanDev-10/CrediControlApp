<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Crear Usuario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <x-auth-session-status class="mb-4" :status="session('success')" />
        <x-auth-session-error class="mb-4" :status="session('error')" />
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" value="Nombre" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required :value="old('name')" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="lastname" value="Apellido" />
                        <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" required :value="old('lastname')" />
                        <x-input-error :messages="$errors->get('lastname')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" value="Correo" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required :value="old('email')" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password" value="Contraseña" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2" onclick="event.preventDefault(); confirmExit();">
                            Cancelar
                        </a>
                        <x-terciary-button>Crear</x-terciary-button>
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
        text: 'Perderás los cambios no guardados.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, salir',
        cancelButtonText: 'No, quedarme'
    }).then(result => {
        if (result.isConfirmed) window.location.href = '{{ route('users.index') }}';
    });
}
</script>
