<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Propietarios - {{ now()->format('Y-m-d') }}</title>
    <style>body{font-family: DejaVu Sans, sans-serif; font-size:12px} table{width:100%;border-collapse:collapse} th,td{padding:6px;border:1px solid #ddd} th{background:#f4f4f4}</style>
</head>
<body>
    <h3>Propietarios - {{ now()->format('Y-m-d') }}</h3>
    <table>
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>CI</th><th>Teléfono</th><th>Email</th></tr>
        </thead>
        <tbody>
            @foreach($propietarios as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nombre_completo }}</td>
                <td>{{ $p->ci }}</td>
                <td>{{ $p->telefono }}</td>
                <td>{{ $p->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>