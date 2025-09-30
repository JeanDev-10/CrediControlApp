<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle de Pago') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Datos de la Deuda --}}
            @include("debts.components.card-debt", ['debt' => $pay->debt, 'showActions' => false])
            {{-- Datos del contacto --}}
            @include("contacts.components.card-contact", ['contact' => $pay->debt->contact, 'showActions' => false])



            {{-- Datos del Pago --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="font-semibold mb-4 text-gray-700 dark:text-gray-300">Datos del pago</h3>
                <div class="grid grid-cols-3 gap-4 text-gray-700 dark:text-gray-300">
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
            {{-- Imágenes --}}
            @if($pay->images->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Imágenes</h3>
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        @foreach($pay->images as $image)
                            <img src="{{ $image->url }}" alt="Imagen del pago {{ $pay->id }}"
                                class="w-full h-32 object-cover rounded cursor-pointer preview-img">
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
        <!-- Modal -->
        <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 items-center justify-center p-4">
            <div class="relative w-full max-w-4xl">
                <button id="close-modal"
                    class="absolute top-3 right-3 z-50 bg-gray-900/80 text-white rounded-full p-1 hover:bg-gray-800"
                    aria-label="Cerrar">✕</button>
                <img id="modal-img" src="" class="mx-auto max-h-[80vh] max-w-full object-contain rounded shadow-lg" />
            </div>
        </div>
    </div>

</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const imgs = document.querySelectorAll(".preview-img");
        const modal = document.getElementById("image-modal");
        const modalImg = document.getElementById("modal-img");
        const closeModal = document.getElementById("close-modal");

        imgs.forEach(img => {
            img.addEventListener("click", function () {
                modalImg.src = this.src;
                modal.classList.remove("hidden");
            });
        });

        closeModal.addEventListener("click", () => {
            modal.classList.add("hidden");
            modalImg.src = "";
        });

        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.add("hidden");
                modalImg.src = "";
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                modal.classList.add("hidden");
                modalImg.src = "";
            }
        });
    });
</script>
