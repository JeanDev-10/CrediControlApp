@props(['contact' => null,'showActions' => true])

<div>
    <div class="mt-1 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-700 dark:text-gray-300">
        <strong>Contacto asociado</strong>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-5">
            <div>
                <x-input-label value="Nombre" />
                <a href="{{ route("contacts.show",$contact->id) }}" class="mt-1 text-blue-400">{{ $contact->name }}</a>
            </div>
            <div>
                <x-input-label value="Apellido" />
                <a href="{{ route("contacts.show",$contact->id) }}" class="mt-1 text-blue-400">{{ $contact->lastname }}</a>
            </div>
            <div>
                <x-input-label value="Teléfono" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->phone ?? '-' }}</p>
            </div>
            <div>
                <x-input-label value="Creado" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->created_at }}
                </p>
            </div>
            <div>
                <x-input-label value="Última actualización" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->updated_at }}
                </p>
            </div>
        </div>
    </div>

    @if($showActions)
        {{-- Acciones --}}
        <div class="flex justify-end gap-3 mt-4">
            @can('update', $contact)
            <a href="{{ route('contacts.edit', $contact) }}">
                <x-primary-button>Editar</x-primary-button>
            </a>
            @endcan

            <a href="{{ route('contacts.index') }}">
                <x-secondary-button>Volver</x-secondary-button>
            </a>

            @can('delete', $contact)
            <form action="{{ route('contacts.destroy', $contact) }}" method="POST"
            onsubmit="return confirm('¿Eliminar este contacto?')">
            @csrf
                @method('DELETE')
                <x-danger-button>Eliminar</x-danger-button>
            </form>
            @endcan
        </div>
    @endif
    </div>
