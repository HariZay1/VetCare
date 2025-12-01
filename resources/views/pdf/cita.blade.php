<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ficha Cita #{{ $cita->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px }
        .section { margin-bottom: 12px }
        .label { font-weight: 700 }
    </style>
</head>
<body>
    <h2>Ficha de Cita #{{ $cita->id }}</h2>

    <div class="section">
        <div><span class="label">Fecha / Hora:</span> {{ $cita->fecha_hora->format('Y-m-d H:i') }}</div>
        <div><span class="label">Mascota:</span> {{ optional($cita->mascota)->nombre }}</div>
        <div><span class="label">Propietario:</span> {{ optional($cita->propietario)->nombre_completo }}</div>
        <div><span class="label">Veterinario:</span> {{ optional($cita->veterinario)->nombre_completo ?? '-' }}</div>
    </div>

    <div class="section">
        <div class="label">Motivo</div>
        <div>{{ $cita->motivo }}</div>
    </div>

    @if($cita->diagnostico)
    <div class="section">
        <div class="label">Diagnóstico</div>
        <div>{{ $cita->diagnostico }}</div>
    </div>
    @endif

    @if($cita->receta)
    <div class="section">
        <div class="label">Receta</div>
        <div>{{ $cita->receta }}</div>
    </div>
    @endif

</body>
</html>