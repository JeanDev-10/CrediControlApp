<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pagos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Usuario: {{ $user->name }} {{ $user->lastname }}</h2>

    @if(collect($filters)->filter()->isNotEmpty())
        <h4>Filtros aplicados:</h4>
        <ul>
            @if(!empty($filters['contact_name']))
                <li><strong>Nombre del contacto:</strong> {{ $filters['contact_name'] }}</li>
            @endif

            @if(!empty($filters['quantity']))
                <li><strong>Cantidad:</strong> {{ $filters['quantity'] }}</li>
            @endif

            @if(!empty($filters['date']))
                <li><strong>Fecha:</strong> {{ $filters['date'] }}</li>
            @endif
        </ul>
    @else
        <p>No se aplicaron filtros.</p>
    @endif

    <h2>Reporte de Pagos</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Contacto</th>
                <th>Descripción deuda</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pays as $index => $pay)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pay->debt->contact->name ?? '-' }} {{ $pay->debt->contact->lastname ?? '' }}</td>
                    <td>{{ ucfirst($pay->debt->description) }}</td>
                    <td>${{ number_format($pay->quantity, 2) }}</td>
                    <td>{{ $pay->date->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($pay->debt->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
