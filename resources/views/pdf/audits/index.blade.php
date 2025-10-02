<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditoría</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        pre {
            background-color: #f9fafb;
            padding: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte de auditoria</h2>
        <p><strong>Generado por:</strong> {{ $user->name }} {{ $user->lastname }}</p>
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Acción</th>
                <th>Entidad</th>
                <th>ID</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ class_basename($log->subject_type) ?? '-' }}</td>
                <td>{{ $log->subject_id }}</td>
                <td>
                    @if($log->properties)
                        <pre>{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
