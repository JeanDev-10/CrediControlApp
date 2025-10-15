<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de contactos</title>
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
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <p><strong>Generado por:</strong> {{ $user->name }} {{ $user->lastname }}</p>
    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    @if(collect($filters)->filter()->isNotEmpty())
        <h4>Filtros aplicados:</h4>
        <ul>
            @if(!empty($filters['name']))
                <li><strong>Nombres:</strong> {{ $filters['name'] }}</li>
            @endif

            @if(!empty($filters['lastname']))
                <li><strong>Apellidos:</strong> {{ $filters['lastname'] }}</li>
            @endif
        </ul>
    @else
        <p>No se aplicaron filtros.</p>
    @endif
    <h2>Reporte de mis contactos</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Creado</th>
                <th>Actualizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $index => $contact)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->lastname }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($contact->created_at!=$contact->updated_at)
                            {{ $contact->updated_at->format('d/m/Y H:i') }}
                        @else
                            No ha sido actualizado
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">No se encontraron contactos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
