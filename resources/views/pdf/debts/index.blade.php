<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Deudas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
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

            @if(!empty($filters['contact_name']))
                <li><strong>Nombre del contacto:</strong> {{ $filters['contact_name'] }}</li>
            @endif
            @if(!empty($filters['date_start']))
                <li><strong>Fecha de inicio:</strong> {{ $filters['date_start'] }}</li>
            @endif
            @if(!empty($filters['status']))
                <li><strong>Estado:</strong> {{ $filters['status'] }}</li>
            @endif
        </ul>
    @else
        <p>No se aplicaron filtros.</p>
    @endif
    <h2>Reporte de deudas </h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Descripción</th>
                <th>Contacto</th>
                <th>Cantidad</th>
                <th>Fecha Inicio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($debts as $index => $debt)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ ucfirst($debt->description) }}</td>
                    <td>{{ $debt->contact->name ?? '-' }} {{ $debt->contact->lastname ?? '' }}</td>
                    <td>${{ number_format($debt->quantity, 2,'.',',') }}</td>
                    <td>{{ $debt->date_start->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($debt->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">No se encontraron deudas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
