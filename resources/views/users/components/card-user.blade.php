@props(['user'])

<div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-700 dark:text-gray-300">
    <strong>Información del Usuario</strong>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-5">
        <div>
            <x-input-label value="Nombre" />
            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
        </div>
        <div>
            <x-input-label value="Apellido" />
            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->lastname }}</p>
        </div>
        <div>
            <x-input-label value="Correo" />
            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
        </div>
        <div>
            <x-input-label value="Estado" />
            <span
                class="mt-1 inline-block px-3 py-1 rounded-full text-white {{ $user->is_active ? 'bg-green-600' : 'bg-red-600' }}">
                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <div>
            <x-input-label value="Creado" />
            <p class="mt-1 text-gray-900 dark:text-gray-100 fecha-entrada" data-fecha="{{ $user->created_at }}"></p>
        </div>
        <div>
            <x-input-label value="Última actualización" />
            @if ($user->created_at != $user->updated_at)
                <p class="mt-1 text-gray-900 dark:text-gray-100 fecha-entrada" data-fecha="{{ $user->updated_at }}">
            @else
                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                        No ha sido actualizado
                @endif
        </div>
    </div>
</div>

<div class="flex justify-end gap-3 mt-4">
    <x-link-button href="{{ route('users.index') }}" variant="secondary">
        Volver
    </x-link-button>
    <x-link-button href="{{ route('users.edit', ['user' => $user, 'redirect_to' => route('users.show', $user)]) }}" >
        Editar
    </x-link-button>
    @can('update', $user)
        <form method="POST"
            action="{{ route('users.toggleIsActive', ['user' => $user, 'redirect_to' => route('users.show', $user)]) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="redirect_to" value="{{ route("users.show", $user) }}">
            <x-primary-button type="submit">Cambiar estado</x-primary-button>
        </form>
    @endcan
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.fecha-entrada').forEach((el) => {
            const fecha = el.dataset.fecha;
            el.innerText = dayjs(fecha).fromNow();
        });
    });
</script>
