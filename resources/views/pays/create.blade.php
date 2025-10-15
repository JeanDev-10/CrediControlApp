<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuevo Pago') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <x-auth-session-status class="mb-4" :status="session('success')" />
                <x-auth-session-error class="mb-4" :status="session('error')" />
                {{-- Formulario de creación de pago --}}
                <form method="POST" action="{{ route('pays.store') }}" enctype="multipart/form-data">
                    @csrf
                      <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">

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
                            id="images"
                        />
                        @foreach ($errors->get('images') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                        @foreach ($errors->get('images.*') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                        {{-- Contenedor de previsualización --}}
    <div id="preview-container" class="grid grid-cols-1 sm:grid-cols-4 gap-2 mt-3"></div>
                    </div>
                    {{-- Modal para imagen expandida --}}
<div id="image-modal"
    class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-50">
    <span id="close-modal"
        class="absolute top-5 right-8 text-white text-3xl cursor-pointer">&times;</span>
    <img id="modal-img" src="" class="max-w-full max-h-full rounded shadow-lg" />
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("images");
    const previewContainer = document.getElementById("preview-container");
    const modal = document.getElementById("image-modal");
    const modalImg = document.getElementById("modal-img");
    const closeModal = document.getElementById("close-modal");

    // Previsualización
    input.addEventListener("change", function () {
        previewContainer.innerHTML = "";

        Array.from(this.files).forEach(file => {
            if (!file.type.startsWith("image/")) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("w-full", "h-24", "object-cover", "rounded", "border", "cursor-pointer");

                // Al hacer click -> mostrar modal
                img.addEventListener("click", () => {
                    modal.classList.remove("hidden");
                    modalImg.src = img.src;
                });

                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // Cerrar modal
    closeModal.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Cerrar modal al hacer click fuera de la imagen
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("hidden");
        }
    });
});
</script>
