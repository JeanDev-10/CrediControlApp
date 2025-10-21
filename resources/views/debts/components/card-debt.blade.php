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

                <p class="mt-1 text-gray-900 dark:text-gray-100">${{ number_format($debt->quantity, 2, '.', ',') }}</p>
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
    @if ($showActions)

        <div class="mt-5 flex justify-end gap-3 flex-wrap">
            <a
                href="{{ route('debts.exportWithPays', array_merge(['debt' => $debt], request()->only(['quantity', 'date']))) }}"
                 target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Exportar PDF
            </a>
            @can('markAsPaid', $debt)
                <form action="{{ route('debts.pay', $debt) }}" method="POST" id="markAsPaidForm">
                    @csrf
                    <x-secondary-button onclick="event.preventDefault();markAsPaid()">Marcar pagada</x-secondary-button>
                </form>
            @endcan
            @can('update', $debt)
                <a href="{{ route('debts.edit', ['debt' => $debt, 'redirect_to' => route('debts.show', $debt)]) }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" >
                    Editar
                </a>
            @endcan
            <x-link-button href="{{ route('debts.index') }}" variant="secondary">
                Volver
            </x-link-button>
            @can('delete', $debt)
                <form method="POST" action="{{ route('debts.destroy', $debt) }}" id="delete-form-{{ $debt->id }}">
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
