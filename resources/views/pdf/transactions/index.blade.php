<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Transacciones</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Usuario: {{ $user->name }} {{ $user->lastname }} </h2>
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
    @endif
    <h2>Reporte de mis Transacciones </h2>
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
            @foreach($transactions as $index=>$tx)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $tx->description }}</td>
                    <td>{{ ucfirst($tx->type) }}</td>
                    <td>${{ number_format($tx->quantity) }}</td>
                    <td>${{ number_format($tx->previus_quantity) }}</td>
                    <td>${{ number_format($tx->after_quantity) }}</td>
                    <td>{{ $tx->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
