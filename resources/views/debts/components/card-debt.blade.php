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

                <p class="mt-1 text-gray-900 dark:text-gray-100">${{ number_format($debt->quantity,2,'.', ',') }}</p>
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

                <p class="mt-1 text-gray-900 dark:text-gray-100 fecha-entrada" data-fecha="{{ $debt->created_at }}"></p>
            </div>
            <div>
                <x-input-label value="Última actualización" />
                @if ($debt->created_at != $debt->updated_at)
                    <p class="mt-1 text-gray-900 dark:text-gray-100 fecha-entrada" data-fecha="{{ $debt->updated_at }}"></p>
                @else
                    <p class="mt-1 text-gray-900 dark:text-gray-100">No ha sido actualizado</p>

                @endif

            </div>
        </div>
    </div>

    @if($showActions)
        <div class="mt-5 flex justify-end gap-3 flex-wrap">
            <div>
                <a target="_blank"
                    href="{{ route('debts.exportWithPays', ["debt" => $debt] + request()->only(['quantity', 'date'])) }}">
                    <x-terciary-button type="button">
                        Exportar PDF
                    </x-terciary-button>
                </a>
            </div>
            @can('markAsPaid', $debt)
                <form action="{{ route('debts.pay', $debt) }}" method="POST" id="markAsPaidForm">
                    @csrf
                    <x-primary-button onclick="event.preventDefault();markAsPaid()">Marcar pagada</x-primary-button>
                </form>
            @endcan
            @can('update', $debt)
                <a href="{{ route('debts.edit', ['debt' => $debt, 'redirect_to' => route('debts.show', $debt)]) }}">
                    <x-terciary-button type="button">Editar</x-terciary-button>
                </a>
            @endcan
            <a href="{{ route('debts.index') }}">
                <x-secondary-button>Volver</x-secondary-button>
            </a>
            @can('delete', $debt)
                <form method="POST" action="{{ route('debts.destroy', $debt) }}"
                    id="delete-form-{{ $debt->id }}">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="button" onclick="confirmDelete({{ $debt->id }})">Eliminar</x-danger-button>
                </form>
            @endcan
        </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Seleccionamos todas las celdas que tienen las fechas
        const fechaElements = document.querySelectorAll('.fecha-entrada');

        // Recorremos cada celda y mostramos la fecha relativa
        fechaElements.forEach((element) => {
            const fecha = element.getAttribute('data-fecha');
            element.innerText = dayjs(fecha).fromNow();
        });
    });
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, se envía el formulario
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
    function markAsPaid(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, marcar como pagada',
            cancelButtonText: 'Cancelar',
            reverseButtons: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, se envía el formulario
                document.getElementById('markAsPaidForm').submit();
            }
        });
    }
</script>
