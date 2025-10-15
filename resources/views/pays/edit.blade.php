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

            {{-- Datos del contacto --}}
            @include("contacts.components.card-contact", ['contact' => $pay->debt->contact, 'showActions' => false])
            {{-- Datos de la Deuda --}}
            @include("debts.components.card-debt", ['debt' => $pay->debt, 'showActions' => false])
            {{-- Datos del Pago --}}

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Editar pago</h3>
                <form method="POST" action="{{ route('pays.update', $pay) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
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
                            class="mt-1 w-full" id="images" />
                        @foreach ($errors->get('images') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                        @foreach ($errors->get('images.*') as $message)
                            <x-input-error :messages="$message" class="mt-2" />
                        @endforeach
                    </div>
                    {{-- Contenedor de previsualización --}}
                    <div id="preview-container" class="grid grid-cols-1 sm:grid-cols-4 gap-2 mt-3"></div>
            </div>
            {{-- Modal para imagen expandida --}}
            <div id="image-modal"
                class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-50">
                <span id="close-modal" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">&times;</span>
                <img id="modal-img" src="" class="max-w-full max-h-full rounded shadow-lg" />
            </div>
            <div class="flex justify-end mt-6">
                <a href="{{ route('pays.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2"
                    onclick="event.preventDefault(); confirmExit();">
                    Cancelar
                </a>
                <x-terciary-button class="ml-3">Actualizar</x-terciary-button>
            </div>
            </form>

            @if($pay->images->count() > 0)
                <h3 class="mt-6 text-lg font-semibold text-gray-700 dark:text-gray-300">Imágenes actuales</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-2">
                    @foreach($pay->images as $image)
                        <div class="relative border p-1 rounded">
                            <img src="{{ $image->url }}" class="w-full h-32 object-cover rounded">
                            <form action="{{ route('pays.images.destroy', $image->id) }}" method="POST"
                                class="absolute top-1 right-1" id="delete-form-image-{{ $image->id }}">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="button" onclick="event.preventDefault();confirmDelete({{ $image->id }})"
                                    class="text-xs px-2 py-1">X</x-danger-button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('pays.images.destroyAll', $pay->id) }}" method="POST"
                    class="mt-4 w-3/4 mx-auto sm:w-auto" id="delete-form-images">
                    @csrf @method('DELETE')
                    <x-danger-button onclick="event.preventDefault(); confirmDeleteAllImages()">Eliminar todas las
                        imágenes</x-danger-button>
                </form>
            @endif

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
    function confirmExit() {
        // Obtenemos el valor del campo 'redirect_to'
        const redirectTo = document.querySelector('[name="redirect_to"]')?.value;

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
                window.location.href = redirectTo || '{{ route('pays.index') }}'; // Si no existe un 'redirect_to', redirige al índice
            }
        });
    }
    function confirmDelete(id) {

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Perderás las imágenes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, quedarme',
            reverseButtons: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-image-' + id).submit();
            }
        });
    }
    function confirmDeleteAllImages() {

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Perderás las imágenes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, quedarme',
            reverseButtons: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-images').submit();
            }
        });
    }
</script>
