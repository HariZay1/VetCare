<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Veterinarios - {{ now()->format('Y-m-d') }}</title>
    <style>body{font-family: DejaVu Sans, sans-serif; font-size:12px} table{width:100%;border-collapse:collapse} th,td{padding:6px;border:1px solid #ddd} th{background:#f4f4f4}</style>
</head>
<body>
    <h3>Veterinarios - {{ now()->format('Y-m-d') }}</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Especialidad</th><th>Teléfono</th><th>Usuario</th></tr>
        </thead>
        <tbody>
            @foreach($veterinarios as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->nombre_completo }}</td>
                <td>{{ $v->especialidad }}</td>
                <td>{{ $v->telefono }}</td>
                <td>{{ optional($v->user)->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>