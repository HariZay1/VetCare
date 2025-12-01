<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Listado de Citas - {{ now()->format('Y-m-d') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; border: 1px solid #ddd; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h3>Listado de Citas - {{ now()->format('Y-m-d') }}</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha / Hora</th>
                <th>Mascota</th>
                <th>Propietario</th>
                <th>Veterinario</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($citas as $cita)
            <tr>
                <td>{{ $cita->id }}</td>
                <td>{{ $cita->fecha_hora->format('Y-m-d H:i') }}</td>
                <td>{{ optional($cita->mascota)->nombre }}</td>
                <td>{{ optional($cita->propietario)->nombre_completo }}</td>
                <td>{{ optional($cita->veterinario)->nombre_completo ?? '-' }}</td>
                <td>{{ $cita->motivo }}</td>
                <td>{{ ucfirst($cita->estado) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>