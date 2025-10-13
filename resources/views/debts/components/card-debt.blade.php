@props(['debt' => null, 'showActions' => true])
<div>
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-700 dark:text-gray-300">
        <strong>Deuda asociada</strong>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5">
            <div>
                <x-input-label value="Descripción" />

                <a href="{{ route("debts.show", $debt) }}" class="text-blue-400">{{ $debt->description }}</a>
            </div>
            <div>
                <x-input-label value="Cantidad" />

                <p class="mt-1 text-gray-900 dark:text-gray-100">${{ $debt->quantity }}</p>
            </div>
            <div>
                <x-input-label value="Fecha inicio" />

                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $debt->date_start->format('d/m/Y') }}</p>
            </div>
            <div>
                <x-input-label value="Estado" />
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $debt->status === 'pendiente'
    ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100'
    : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                    {{ ucfirst($debt->status) }}
                </span>
            </div>
            <div>
                <x-input-label value="Creado" />

                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $debt->created_at }}</p>
            </div>
            <div>
                <x-input-label value="Última actualización" />

                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $debt->updated_at }}</p>
            </div>
        </div>
    </div>

    @if($showActions)
        <div class="mt-5 flex justify-end gap-3 flex-wrap">
            <div>
                <a target="_blank" href="{{ route('debts.exportWithPays', ["debt"=>$debt] + request()->only(['quantity','date'])) }}">
                    <x-terciary-button type="button">
                        Exportar PDF
                    </x-terciary-button>
                </a>
            </div>
            @can('markAsPaid', $debt)
                <form action="{{ route('debts.pay', $debt) }}" method="POST">
                    @csrf
                    <x-primary-button onclick="return confirm('Marcar como pagada?')">Marcar pagada</x-primary-button>
                </form>
            @endcan
            @can('update', $debt)
                <a href="{{ route('debts.edit', ['debt'=>$debt,'redirect_to' => route('debts.show', $debt)]) }}">
                    <x-terciary-button type="button">Editar</x-terciary-button>
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
