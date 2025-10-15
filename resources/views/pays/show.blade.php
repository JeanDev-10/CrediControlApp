<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle de Pago') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />

            {{-- Datos de la Deuda --}}
            @include("debts.components.card-debt", ['debt' => $pay->debt, 'showActions' => false])

            {{-- Datos del contacto --}}
            @include("contacts.components.card-contact", ['contact' => $pay->debt->contact, 'showActions' => false])

            {{-- Datos del Pago --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4 text-gray-700 dark:text-gray-300">Datos del pago</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div>
                        <x-input-label value="Cantidad:" />
                        <p class="text-green-600 dark:text-green-400">${{ $pay->quantity }}</p>
                    </div>
                    <div>
                        <x-input-label value="Fecha de pago:" />
                        <p class="text-gray-900 dark:text-gray-100">{{ $pay->date->format('d/m/Y')}}</p>
                    </div>
                    <div>
                        <x-input-label value="Registro del pago:" />
                        <p class="text-gray-900 dark:text-gray-100 fecha-entrada" data-fecha="{{ $pay->created_at }}">
                        </p>
                    </div>
                    <div>
                        <x-input-label value="Actualización del registro:" />
                        @if ($pay->created_at != $pay->updated_at)
                            <p class="text-gray-900 dark:text-gray-100 fecha-entrada" data-fecha="{{ $pay->updated_at }}">
                            </p>
                        @else
                            <p class="text-gray-900 dark:text-gray-100">No ha sido actualizado</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Imágenes --}}
            @if($pay->images->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Imágenes</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-2">
                        @foreach($pay->images as $image)
                            <img src="{{ $image->url }}" alt="Imagen del pago {{ $pay->id }}"
                                class="w-full h-32 object-cover rounded cursor-pointer preview-img" alt="imagen del pago">
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
                    <a href="{{ route('pays.edit', ['pay' => $pay, 'redirect_to' => route('pays.show', $pay->id)]) }}">
                        <x-terciary-button type="button">Editar</x-terciary-button>
                    </a>
                @endcan
                @can('delete', $pay)
                    <form
                        action="{{ route('pays.destroy', ['pay' => $pay, 'redirect_to' => route('debts.show', $pay->debt)]) }}"
                        method="POST" onsubmit="return confirm('Eliminar pago?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>Eliminar</x-danger-button>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Modal -->
        <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 items-center justify-center p-4">
            <div class="relative w-full max-h-screen overflow-hidden">
                <button id="close-modal"
                    class="absolute top-3 right-3 z-50 bg-gray-900/80 text-white rounded-full p-1 hover:bg-gray-800"
                    aria-label="Cerrar">✕</button>
                <img id="modal-img" src="" class="m-auto max-h-[90vh] max-w-full object-contain rounded shadow-lg" />
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Seleccionamos todas las celdas que tienen las fechas

        humanizeDatesOfDebts();
        const imgs = document.querySelectorAll(".preview-img"); // Todas las imágenes dentro del contenedor
        const modal = document.getElementById("image-modal");
        const modalImg = document.getElementById("modal-img");
        const closeModal = document.getElementById("close-modal");

        // Mostrar modal al hacer click en una imagen
        imgs.forEach(img => {
            img.addEventListener("click", function () {
                modalImg.src = this.src; // Establece la imagen seleccionada en el modal
                modal.classList.remove("hidden"); // Muestra el modal
            });
        });

        // Cerrar modal
        closeModal.addEventListener("click", () => {
            modal.classList.add("hidden"); // Ocultar el modal
            modalImg.src = ""; // Limpiar la imagen mostrada
        });

        // Cerrar modal al hacer clic fuera de la imagen
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
                modalImg.src = "";
            }
        });

        // Cerrar el modal al presionar la tecla Escape
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                modal.classList.add("hidden");
                modalImg.src = "";
            }
        });
    });

    const humanizeDatesOfDebts = () => {
        const fechaElements = document.querySelectorAll('.fecha-entrada');

        // Recorremos cada celda y mostramos la fecha relativa
        fechaElements.forEach((element) => {
            const fecha = element.getAttribute('data-fecha');
            element.innerText = dayjs(fecha).fromNow();
        });
    }
</script>
