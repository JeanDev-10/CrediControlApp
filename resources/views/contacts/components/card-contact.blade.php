@props(['contact' => null, 'showActions' => true])

<div>
    <div class="mt-1 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-700 dark:text-gray-300">
        <strong>Contacto asociado</strong>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-5">
            <div>
                <x-input-label value="Nombre" />
                <a href="{{ route("contacts.show", $contact->id) }}" class="mt-1 text-blue-400">{{ $contact->name }}</a>
            </div>
            <div>
                <x-input-label value="Apellido" />
                <a href="{{ route("contacts.show", $contact->id) }}"
                    class="mt-1 text-blue-400">{{ $contact->lastname }}</a>
            </div>
            <div>
                <x-input-label value="Teléfono" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $contact->phone ?? '-' }}</p>
            </div>
            <div>
                <x-input-label value="Creado" />
                <p class="mt-1 text-gray-900 dark:text-gray-100 fecha-entrada" data-fecha="{{ $contact->created_at }}">
                </p>
            </div>
            <div>
                <x-input-label value="Última actualización" />
                @php
                    $hasBeenUpdated = $contact->created_at != $contact->updated_at;
                @endphp
                <p class="mt-1 text-gray-900 dark:text-gray-100 {{ $hasBeenUpdated ? 'fecha-entrada' : '' }}"
                    @if($hasBeenUpdated) data-fecha="{{ $contact->updated_at }}" @endif>
                    {{ $hasBeenUpdated ? '' : 'No ha sido actualizado' }}
                </p>
            </div>

        </div>
    </div>
    @if ($showActions)

        {{-- Acciones --}}
        <div class="flex justify-end gap-3 mt-4">

            <x-link-button href="{{ route('contacts.exportWithDebts',array_merge(['contact'=>$contact],request()->only(['description', 'date_start', 'status']))) }}"  target="_blank"
                variant="tertiary">
                Exportar PDF
            </x-link-button>

            @can('update', $contact)
                <x-link-button
                    href="{{ route('contacts.edit', ['contact' => $contact, 'redirect_to' => route('contacts.show', $contact)]) }}">
                    Editar
                </x-link-button>
            @endcan
            <x-link-button href="{{ route('contacts.index') }}" variant="secondary">
                Volver
            </x-link-button>
            @can('delete', $contact)
                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" id="delete-form-{{ $contact->id }}">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="button" onclick="confirmDelete({{ $contact->id }})">Eliminar</x-danger-button>
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
    function confirmDelete(contactId) {
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
                document.getElementById('delete-form-' + contactId).submit();
            }
        });
    }
</script>
