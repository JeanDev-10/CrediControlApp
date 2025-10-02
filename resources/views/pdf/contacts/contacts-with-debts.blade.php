<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Deudas por Contacto</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2, h3 { margin: 0; padding: 5px 0; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
        .filters ul { margin: 5px 0 0 15px; padding: 0; }
    </style>
</head>
<body>
    {{-- Usuario que genera el reporte --}}
    <div class="header">
        <h2>Reporte de deudas por contacto</h2>
        <p><strong>Generado por:</strong> {{ $user->name }} {{ $user->lastname }}</p>
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Cliente al que se le genera el reporte --}}
    <div class="section">
        <h3>Cliente/Contacto</h3>
        <table>
            <tr>
                <th>Nombre</th>
                <td>{{ $contact->name }}</td>
                <th>Apellido</th>
                <td>{{ $contact->lastname }}</td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td>{{ $contact->phone ?? '-' }}</td>
                <th>Creado</th>
                <td>{{ $contact->created_at }}</td>
            </tr>
            <tr>
                <th>Última actualización</th>
                <td colspan="3">{{ $contact->updated_at }}</td>
            </tr>
        </table>
    </div>

    {{-- Filtros aplicados --}}
    @if(collect($filters)->filter()->isNotEmpty())
        <div class="section filters">
            <h3>Filtros aplicados</h3>
            <ul>
                @if(!empty($filters['description']))
                    <li><strong>Descripción:</strong> {{ $filters['description'] }}</li>
                @endif
                @if(!empty($filters['date_start']))
                    <li><strong>Fecha inicio desde:</strong> {{ $filters['date_start'] }}</li>
                @endif
                @if(!empty($filters['status']))
                    <li><strong>Estado:</strong> {{ ucfirst($filters['status']) }}</li>
                @endif
            </ul>
        </div>
    @else
        <p>No se aplicaron filtros.</p>
    @endif

    {{-- Tabla de deudas --}}
    <div class="section">
        <h3>Deudas del Cliente</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Fecha inicio</th>
                    <th>Estado</th>
                    <th>Creado</th>
                    <th>Actualizado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($debts as $i => $debt)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ ucfirst($debt->description) }}</td>
                        <td>${{ number_format($debt->quantity) }}</td>
                        <td>{{ $debt->date_start->format("d/m/Y") }}</td>
                        <td>{{ ucfirst($debt->status) }}</td>
                        <td>{{ $debt->created_at }}</td>
                        <td>{{ $debt->updated_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #777;">No se encontraron deudas para este contacto.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
