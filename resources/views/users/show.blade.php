<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Detalle del Usuario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-auth-session-status class="mb-4" :status="session('success')" />
            <x-auth-session-error class="mb-4" :status="session('error')" />
            @include('users.components.card-user', ['user' => $user])
        </div>
    </div>
</x-app-layout>
