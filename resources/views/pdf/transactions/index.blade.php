<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de transacciones</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <p><strong>Generado por:</strong> {{ $user->name }} {{ $user->lastname }}</p>
    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    @if(collect($filters)->filter()->isNotEmpty())
        <h4>Filtros aplicados:</h4>
        <ul>
            @if(!empty($filters['description']))
                <li><strong>Descripción:</strong> {{ $filters['description'] }}</li>
            @endif
            @if(!empty($filters['type']))
                <li><strong>Tipo:</strong> {{ $filters['type'] }}</li>
            @endif
            @if(!empty($filters['date']))
                <li><strong>Fecha:</strong> {{ $filters['date'] }}</li>
            @endif
        </ul>
    @else
        <p>No se aplicaron filtros.</p>
    @endif
    <h2>Reporte de mis transacciones </h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Saldo anterior</th>
                <th>Saldo actual</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $tx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tx->description }}</td>
                    <td>{{ ucfirst($tx->type) }}</td>
                    <td>${{ number_format($tx->quantity,2,'.', ',') }}</td>
                    <td>${{ number_format($tx->previus_quantity,2,'.', ',') }}</td>
                    <td>${{ number_format($tx->after_quantity,2,'.', ',') }}</td>
                    <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #777;">No se encontraron transacciones.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
