<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Deuda</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2,
        h3 {
            margin: 0;
            padding: 5px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            margin-bottom: 20px;
        }

        .filters ul {
            margin: 5px 0 0 15px;
            padding: 0;
        }
    </style>
</head>

<body>
    {{-- Usuario que genera el reporte --}}
    <div class="header">
        <h2>Reporte de pagos por deuda</h2>
        <p><strong>Generado por:</strong> {{ $user->name }} {{ $user->lastname }}</p>
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Cliente al que se le genera el reporte --}}
    <div class="section">
        <h3>Cliente/Contacto</h3>
        <table>
            <tr>
                <th>Nombre</th>
                <td>{{ $debt->contact->name }}</td>
                <th>Apellido</th>
                <td>{{ $debt->contact->lastname }}</td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td>{{ $debt->contact->phone ?? '-' }}</td>
                <th>Creado</th>
                <td>{{ $debt->contact->created_at->format("d/m/Y H:i") }}</td>
            </tr>
            <tr>
                <th>Última actualización</th>
                <td colspan="3">
                    @if ($debt->contact->created_at != $debt->contact->updated_at)
                        {{ $debt->contact->updated_at->format("d/m/Y H:i") }}
                    @else
                        No ha sido actualizado
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <h3>Detalle de Deuda #{{ $debt->id }}</h3>

    <div class="section">
        <table>
            <tr>
                <th>Descripción:</th>
                <td>{{ $debt->description }}</td>
                <th>Cantidad total:</th>
                <td>${{ $debt->quantity }}</td>
            </tr>
            <tr>
                <th>Estado: </th>
                <td>{{ ucfirst($debt->status) }}</td>
                <th>Fecha inicio:</th>
                <td>{{ $debt->date_start->format('d/m/Y')  }}</td>
            </tr>
            <tr>
                <th>Creado:</th>
                <td>{{ $debt->created_at->format("d/m/Y H:i") }}</td>
                <th>Actualizado:</th>
                <td>
                    @if ($debt->created_at != $debt->updated_at)
                        {{ $debt->updated_at->format("d/m/Y H:i") }}
                    @else
                        No ha sido actualizado
                    @endif
                </td>
            </tr>
            <tr>
                <th>Restante:</th>
                <td colspan="3">{{ $remaining >= 0 ? '$' . $remaining : '---' }}</td>
            </tr>
            <tr>
                <th>Total pagado:</th>
                <td colspan="3">${{ $totalPaid }}</td>
            </tr>
        </table>
    </div>
    <h3>Pagos</h3>
    {{-- Filtros aplicados --}}
    @if(collect($filters)->filter()->isNotEmpty())
        <div class="section filters">
            <h3>Filtros aplicados</h3>
            <ul>
                @if(!empty($filters['quantity']))
                    <li><strong>Cantidad:</strong> {{ $filters['quantity'] }}</li>
                @endif
                @if(!empty($filters['date']))
                    <li><strong>Fecha:</strong> {{ $filters['date'] }}</li>
                @endif
            </ul>
        </div>
    @else
        <p>No se aplicaron filtros.</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Creado</th>
                <th>Actualizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pays as $index => $pay)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>${{ $pay->quantity }}</td>
                    <td>{{ $pay->date->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($pay->debt->status) }}</td>
                    <td>{{ $pay->created_at}}</td>
                    <td>
                        @if ($pay->created_at!=$pay->updated_at)
                            {{ $pay->updated_at }}
                        @else
                            No ha sido actualizado
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">No hay pagos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
