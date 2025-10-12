@props(['data' => null])
<div>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center mt-4">
        {{ __('Contactos con más deudas pendientes') }}
    </h2>

    <div class="py-6 sm:px-4 lg:px-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtros --}}
            <form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                <div class="w-full sm:w-auto">
                    <x-input-label for="from" value="Desde" />
                    <x-text-input type="date" name="fromDebts" value="{{ request('fromDebts') }}" class="mt-1 w-full" />
                </div>
                <div class="w-full sm:w-auto">
                    <x-input-label for="to" value="Hasta" />
                    <x-text-input type="date" name="toDebts" value="{{ request('toDebts') }}" class="mt-1 w-full" />
                </div>
                <div class="w-full sm:w-auto">
                    <x-input-label for="limit" value="Cantidad de contactos" />
                    <x-text-input type="number" name="limitDebts" value="{{ request('limitDebts') }}" min="1" max="20"
                        class="mt-1 w-full" />
                </div>
                <div class="w-full flex justify-center items-center sm:justify-start sm:items-start sm:w-auto sm:mb-2">
                    <x-primary-button>Filtrar</x-primary-button>
                </div>
            </form>

        </div>

        {{-- Gráfico --}}
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-gray-800 dark:text-gray-100 w-full mx-auto">
            <canvas id="topDebtorsChart" height="120"></canvas>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const isDarkDebts = document.documentElement.classList.contains('dark');
        const textColorDebts = isDarkDebts ? '#fff' : '#111';
        const gridColorDebts = isDarkDebts ? '#444' : '#ddd';

        const ctxDebts = document.getElementById('topDebtorsChart');

        const labels = {!! json_encode($data->pluck('contact.name')) !!};
        const values = {!! json_encode($data->pluck('total_amount')) !!};

        new Chart(ctxDebts, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monto total pendiente ($)',
                    data: values,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColorDebts }
                    },
                    title: {
                        display: true,
                        text: 'Top ' + values.length + ' contactos con más deudas pendientes',
                        color: textColorDebts
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColorDebts },
                        grid: { color: gridColorDebts }
                    },
                    x: {
                        ticks: { color: textColorDebts },
                        grid: { color: gridColorDebts }
                    }
                }
            }
        });
    </script>
</div>
