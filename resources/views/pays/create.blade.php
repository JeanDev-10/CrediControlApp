<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuevo Pago') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <x-auth-session-error class="mb-4" :status="session('status')" />
                {{-- Formulario de creación de pago --}}
                <form method="POST" action="{{ route('pays.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="quantity" value="Cantidad" />
                        <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="w-full mt-1"
                            value="{{ old('quantity') }}" required />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            <x-input-error :messages="$errors->get('debt')" class="mt-2" />

                    </div>
                    <div class="mb-4">
                        <x-input-label for="date" value="Fecha" />
                        <x-text-input id="date" name="date" type="date" class="w-full mt-1"
                            value="{{ old('date') }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="debt_id" value="Deuda" />
                        <select id="debt_id" name="debt_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                            <option value="">-- Seleccionar deuda --</option>
                            @foreach($debts as $debt)
                                <option value="{{ $debt->id }}" @selected(old('debt_id') == $debt->id)>
                                    {{ $debt->description }} - {{ $debt->contact->name ?? '' }} {{ $debt->contact->lastname ?? '' }} {{ $debt->contact->phone ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('debt_id')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="images" value="Agregar imágenes" />
                        <input
                            type="file"
                            name="images[]"
                            multiple
                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            class="mt-1 w-full"
                        />
                        @foreach ($errors->get('images') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                        @foreach ($errors->get('images.*') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('pays.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2">
                            Cancelar
                        </a>
                        <x-terciary-button class="ml-3">Guardar</x-terciary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
