<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tratamientos - {{ now()->format('Y-m-d') }}</title>
    <style>body{font-family: DejaVu Sans, sans-serif; font-size:12px} table{width:100%;border-collapse:collapse} th,td{padding:6px;border:1px solid #ddd} th{background:#f4f4f4}</style>
</head>
<body>
    <h3>Tratamientos - {{ now()->format('Y-m-d') }}</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Motivo Cita</th><th>Mascota</th><th>Descripción</th><th>Costo</th></tr>
        </thead>
        <tbody>
            @foreach($tratamientos as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ optional($t->cita)->motivo }}</td>
                <td>{{ optional($t->mascota)->nombre }}</td>
                <td>{{ $t->descripcion }}</td>
                <td>{{ $t->costo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>