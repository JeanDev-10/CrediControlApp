<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de usuarios</title>
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

    @if(
            collect($filters)->filter(function ($value) {
                return $value !== null && $value !== '';
            })->isNotEmpty()
        )
        <h4>Filtros aplicados:</h4>
        <ul>
            @if(!empty($filters['name']))
                <li><strong>Nombres:</strong> {{ $filters['name'] }}</li>
            @endif
            @if(!empty($filters['lastname']))
                <li><strong>Apellidos:</strong> {{ $filters['lastname'] }}</li>
            @endif
            @if(!empty($filters['email']))
                <li><strong>Email:</strong> {{ $filters['email'] }}</li>
            @endif
            @if(isset($filters['is_active']))
                <li><strong>Estado:</strong> {{ $filters['is_active'] === 1 ? 'Activo' : 'Inactivo' }}</li>
            @endif
        </ul>
    @else
        <p>No se aplicaron filtros.</p>
    @endif

    <h2>Reporte de usuarios</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Creado</th>
                <th>Actualizado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $u)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->lastname }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->is_active ? 'Activo' : 'Inactivo' }}</td>
                    <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($u->created_at != $u->updated_at)
                            {{ $u->updated_at->format('d/m/Y H:i') }}
                        @else
                            No ha sido actualizado
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #777;">No se encontraron usuarios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
