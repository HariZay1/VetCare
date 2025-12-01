<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mascotas - {{ now()->format('Y-m-d') }}</title>
    <style>body{font-family: DejaVu Sans, sans-serif; font-size:12px} table{width:100%;border-collapse:collapse} th,td{padding:6px;border:1px solid #ddd} th{background:#f4f4f4}</style>
</head>
<body>
    <h3>Mascotas - {{ now()->format('Y-m-d') }}</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Especie</th><th>Raza</th><th>Propietario</th></tr>
        </thead>
        <tbody>
            @foreach($mascotas as $m)
            <tr>
                <td>{{ $m->id }}</td>
                <td>{{ $m->nombre }}</td>
                <td>{{ $m->especie }}</td>
                <td>{{ $m->raza }}</td>
                <td>{{ optional($m->propietario)->nombre_completo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>