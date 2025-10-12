@props(['chartData' => null])
<div>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center mt-4">
        {{ __('Reporte de Ingresos vs Egresos (Mes Actual)') }}
    </h2>

    <div class="py-6 px-2 sm:px-4 lg:px-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Filtros --}}
            <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
                <div class="w-full sm:w-auto">
                    <x-input-label for="from" value="Desde" />
                    <x-text-input id="from" name="fromTransactions" type="date"
                        value="{{ request('fromTransactions') }}" class="mt-1 block w-full border rounded-md" />
                </div>

                <div class="w-full sm:w-auto">
                    <x-input-label for="to" value="Hasta" />
                    <x-text-input id="to" name="toTransactions" type="date" value="{{ request('toTransactions') }}"
                        class="mt-1 block w-full border rounded-md" />
                </div>

                <div class="w-full flex justify-center items-center sm:justify-end sm:items-end sm:w-auto sm:mb-auto">
                    <x-primary-button class="mt-6">
                        Filtrar
                    </x-primary-button>
                </div>
            </form>
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 w-full mx-auto sm:w-2/3">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const isDark = document.documentElement.classList.contains('dark'); // Detectar si está en modo oscuro
        const textColor = isDark ? '#fff' : '#111'; // Color para los textos
        const gridColor = isDark ? '#444' : '#ddd'; // Color para las líneas de la cuadrícula
        const ctx = document.getElementById('incomeExpenseChart');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Total en USD',
                    data: @json($chartData['data']),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',  // azul - ingresos
                        'rgba(255, 99, 132, 0.6)'   // rojo - egresos
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor }
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Ingresos y Egresos',
                        color: textColor
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor // Cambiar color de las etiquetas del eje Y
                    },
                    grid: {
                        color: gridColor // Cambiar el color de las líneas de la cuadrícula
                    }
                },
                x: {
                    ticks: {
                        color: textColor // Cambiar color de las etiquetas del eje X
                    },
                    grid: {
                        color: gridColor // Cambiar el color de las líneas de la cuadrícula
                    }
                }
                }
            });
    </script>
</div>
