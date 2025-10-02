<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Deudas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Usuario: {{ $user->name }} {{ $user->lastname }} </h2>
    <h2>Reporte de Deudas </h2>
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
            @foreach($debts as $index=>$debt)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ ucfirst($debt->description) }}</td>
                    <td>{{ $debt->contact->name ?? '-' }} {{ $debt->contact->lastname ?? '' }}</td>
                    <td>${{ number_format($debt->quantity, 2) }}</td>
                    <td>{{ $debt->date_start->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($debt->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
