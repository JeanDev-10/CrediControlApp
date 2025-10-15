@props(['messages'])
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mx-auto mt-2 sm:mx-0 sm:mt-0">
    @if($budget)
        {{ __('Saldo actual') }} <span class="text-green-600 dark:text-green-400">${{ $budget->quantity }}</span>
    @else
        <span class="text-red-500">
            {{ __('No hay presupuesto configurado') }}
        </span>
    @endif
</h2>
