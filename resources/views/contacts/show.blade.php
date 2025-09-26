{{-- resources/views/contacts/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle del Contacto') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label value="Nombre" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->name }}</p>
                    </div>
                    <div>
                        <x-input-label value="Apellido" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->lastname }}</p>
                    </div>
                    <div>
                        <x-input-label value="Teléfono" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <x-input-label value="Creado" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <x-input-label value="Última actualización" />
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex justify-end gap-3">
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
        </div>
    </div>
</x-app-layout>
