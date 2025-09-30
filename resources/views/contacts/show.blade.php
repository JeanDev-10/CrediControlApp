{{-- resources/views/contacts/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle del Contacto') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('contacts.components.card-contact', ['contact' => $contact])
        </div>
        {{-- Botón y tabla de deudas --}}
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 mt-16">
            <button id="toggle-debts" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Mostrar Deudas
            </button>

            {{-- <div id="debts-section" class="mt-4 hidden">
                <x-debts.table :contact="$contact" :debts="$debts" />
            </div> --}}
        </div>
    </div>
</x-app-layout>
<script>
    document.getElementById("toggle-debts").addEventListener("click", function () {
        const section = document.getElementById("debts-section");
        section.classList.toggle("hidden");
        this.textContent = section.classList.contains("hidden") ? "Mostrar Deudas" : "Ocultar Deudas";
    });
</script>
