<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle de Deuda') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                    <div>
                        <strong>Descripción:</strong>
                        <p>{{ $debt->description }}</p>
                    </div>
                    <div>
                        <strong>Cantidad:</strong>
                        <p>{{ $debt->quantity }}</p>
                    </div>
                    <div>
                        <strong>Contacto:</strong>
                        <div class="flex items-center gap-3">
                            <p>{{ $debt->contact->name }} {{ $debt->contact->lastname }} {{$debt->contact->phone}}</p>
                            <a href="{{ route("contacts.show", $debt->contact) }}">
                                <x-terciary-button>Ver Contacto</x-terciary-button>
                            </a>
                        </div>
                    </div>
                    <div>
                        <strong>Fecha inicio:</strong>
                        <p>{{ $debt->date_start->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <strong>Estado:</strong>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $debt->status === 'pendiente'
    ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100'
    : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                            {{ ucfirst($debt->status) }}
                        </span>
                    </div>
                </div>
            </div>

                <div class="mt-5 flex justify-end gap-3">
                    @can('update', $debt)
                        <a href="{{ route('debts.edit', $debt) }}">
                            <x-primary-button>Editar</x-primary-button>
                        </a>
                    @endcan
                    <a href="{{ route('debts.index') }}">
                        <x-secondary-button>Volver</x-secondary-button>
                    </a>
                    @can('delete', $debt)
                        <form method="POST" action="{{ route('debts.destroy', $debt) }}"
                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta deuda?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Eliminar</x-danger-button>
                        </form>
                    @endcan
                </div>
        </div>
    </div>
</x-app-layout>
