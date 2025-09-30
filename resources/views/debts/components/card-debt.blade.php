@props(['debt' => null, 'showActions' => true])
<div>
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-700 dark:text-gray-300">
        <strong>Deuda asociada</strong>
        <div class="grid grid-cols-3 gap-4 mt-5">
            <div>
                <strong>Descripción:</strong>
                <a href="{{ route("debts.show", $debt) }}" class="text-blue-400">{{ $debt->description }}</a>
            </div>
            <div>
                <strong>Cantidad:</strong>
                <p>${{ $debt->quantity }}</p>
            </div>
            <div>
                <strong>Fecha inicio:</strong>
                <p>{{ $debt->date_start->format('d/m/Y') }}</p>
            </div>
            <div>
                <strong>Estado:</strong>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $debt->status === 'pendiente'
    ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100'
    : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                    {{ ucfirst($debt->status) }}
                </span>
            </div>
        </div>
    </div>

    @if($showActions)
        <div class="mt-5 flex justify-end gap-3">
            @can('markAsPaid', $debt)
                <form action="{{ route('debts.pay', $debt) }}" method="POST">
                    @csrf
                    <x-primary-button onclick="return confirm('Marcar como pagada?')">Marcar pagada</x-primary-button>
                </form>
            @endcan
            @can('markAsPaid', $debt)
                <a href="{{ route('pays.create', ['debt_id' => $debt->id]) }}">
                    <x-secondary-button>Hacer pago</x-secondary-button>
                </a>
            @endcan
            @can('update', $debt)
                <a href="{{ route('debts.edit', $debt) }}">
                    <x-terciary-button>Editar</x-terciary-button>
                </a>
            @endcan
            <a href="{{ route('debts.index') }}">
                <x-secondary-button>Volver</x-secondary-button>
            </a>
            @can('delete', $debt)
                <form method="POST" action="{{ route('debts.destroy', $debt) }}"
                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta deuda?');">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>Eliminar</x-danger-button>
                </form>
            @endcan
        </div>
    @endif
</div>
