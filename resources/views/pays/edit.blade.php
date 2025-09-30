<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Pago') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-auth-session-error class="mb-4" :status="session('error')" />
            <x-auth-session-status class="mb-4" :status="session('status')" />


            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Datos del pago</h3>
                <div class="grid grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                    <div>
                        <strong>Nombre del contacto:</strong>
                        <p>{{ $pay->debt->contact->name }} {{$pay->debt->contact->lastname}}</p>
                    </div>
                    <div>
                        <strong>Descripción de la deuda:</strong>
                        <p>{{ $pay->debt->description}}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('pays.update', $pay) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="quantity" value="Cantidad" />
                        <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="w-full mt-1"
                            value="{{ old('quantity', $pay->quantity) }}" required />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="date" value="Fecha" />
                        <x-text-input id="date" name="date" type="date" class="w-full mt-1"
                            value="{{ old('date', $pay->date->format('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <x-text-input id="debt_id" name="debt_id" type="hidden" value="{{ $pay->debt_id }}" />
                    <div class="mb-4">
                        <x-input-label for="images" value="Agregar imágenes" />
                        <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            class="mt-1 w-full" />
                        @foreach ($errors->get('images') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                        @foreach ($errors->get('images.*') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('pays.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2">
                            Cancelar
                        </a>
                        <x-terciary-button class="ml-3">Actualizar</x-terciary-button>
                    </div>
                </form>

                @if($pay->images->count() > 0)
                    <h3 class="mt-6 text-lg font-semibold text-gray-700 dark:text-gray-300">Imágenes actuales</h3>
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        @foreach($pay->images as $image)
                            <div class="relative border p-1 rounded">
                                <img src="{{ $image->url }}" class="w-full h-32 object-cover rounded">
                                <form action="{{ route('pays.images.destroy', $image->id) }}" method="POST"
                                    class="absolute top-1 right-1">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button onclick="return confirm('Eliminar imagen?')"
                                        class="text-xs px-2 py-1">X</x-danger-button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('pays.images.destroyAll', $pay->id) }}" method="POST" class="mt-4">
                        @csrf @method('DELETE')
                        <x-danger-button onclick="return confirm('Eliminar todas las imágenes?')">Eliminar todas las
                            imágenes</x-danger-button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
