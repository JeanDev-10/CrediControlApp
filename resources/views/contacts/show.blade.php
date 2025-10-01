{{-- resources/views/contacts/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle del Contacto') }}
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />
            @include('contacts.components.card-contact', ['contact' => $contact])
        </div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-10">
            @include("debts.components.table-debts", ['debts' => $debts, 'contact' => $contact])
        </div>
    </div>
</x-app-layout>
