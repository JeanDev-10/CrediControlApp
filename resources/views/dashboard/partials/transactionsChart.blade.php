@props(['chartData' => null])
<div>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center mt-4">
        {{ __('Reporte de Ingresos vs Egresos (Mes Actual)') }}
    </h2>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
             {{-- Filtros --}}
                <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
                    <div>
                        <x-input-label for="from" value="Desde" />
                        <x-text-input id="from" name="from" type="date"
                            value="{{ request('from') }}" class="mt-1 block w-full border rounded-md" />
                    </div>

                    <div>
                        <x-input-label for="to" value="Hasta" />
                        <x-text-input id="to" name="to" type="date"
                            value="{{ request('to') }}" class="mt-1 block w-full border rounded-md" />
                    </div>

                    <div>
                        <x-primary-button class="mt-6">
                            Filtrar
                        </x-primary-button>
                    </div>
                </form>
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 w-3/4 mx-auto">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
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
                        labels: { color: '#0000FF' }
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Ingresos y Egresos',
                        color: '#0000FF'
                    }
                }
            }
        });
    </script>
</div>
