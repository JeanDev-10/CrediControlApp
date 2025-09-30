<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle de Pago') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Datos del Pago --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Datos del pago</h3>
                <div class="grid grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                    <div>
                        <strong>Cantidad:</strong>
                        <p>${{ $pay->quantity }}</p>
                    </div>
                    <div>
                        <strong>Fecha de pago:</strong>
                        <p>{{ $pay->date->format('d/m/Y')}}</p>
                    </div>
                    <div>
                        <strong>Registro del pago:</strong>
                        <p>{{ $pay->created_at }}</p>
                    </div>
                    <div>
                        <strong>Actualización del registro:</strong>
                        <p>{{ $pay->updated_at }}</p>
                    </div>
                </div>
            </div>

            {{-- Datos de la Deuda --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Datos de la deuda</h3>
                <div class="grid grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                    <div>
                        <strong>Descripción:</strong>
                        <a href="{{ route('debts.show', $pay->debt->contact) }}"
                            class="text-blue-700 dark:text-blue-300 hover:underline">
                            {{ $pay->debt->description }}
                        </a>
                    </div>
                    <div>
                        <strong>Contacto:</strong>
                        <a href="{{ route('contacts.show', $pay->debt->contact) }}"
                            class="text-blue-700 dark:text-blue-300 hover:underline">
                            {{ $pay->debt->contact->name }} {{ $pay->debt->contact->lastname }}
                        </a>
                    </div>
                    <div>
                        <strong>Estado:</strong>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-semibold {{ $pay->debt->status === 'pendiente' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                            {{ ucfirst($pay->debt->status) }}
                        </span>
                    </div>
                    <div>
                        <strong>Deuda total:</strong>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-semibold {{ $pay->debt->status === 'pendiente' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                            ${{ $pay->debt->quantity }}
                        </span>
                    </div>
                    <div>
                        <strong>Creada:</strong>
                        <p>
                            {{ $pay->debt->created_at}}
                        </p>
                    </div>
                    <div>
                        <strong>Actualizada:</strong>
                        <p>
                            {{ $pay->debt->updated_at }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Imágenes --}}
            @if($pay->images->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Imágenes</h3>
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        @foreach($pay->images as $image)
                            <img src="{{ $image->url }}" class="w-full h-32 object-cover rounded">
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Acciones --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('pays.index') }}">
                    <x-secondary-button>Volver</x-secondary-button>
                </a>
                @can('update', $pay)
                    <a href="{{ route('pays.edit', $pay) }}">
                        <x-terciary-button>Editar</x-terciary-button>
                    </a>
                @endcan
                @can('delete', $pay)
                    <form action="{{ route('pays.destroy', $pay) }}" method="POST"
                        onsubmit="return confirm('Eliminar pago?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>Eliminar</x-danger-button>
                    </form>
                @endcan
            </div>

        </div>
    </div>
</x-app-layout>
