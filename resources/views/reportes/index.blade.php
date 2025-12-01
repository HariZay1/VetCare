@extends('layouts.app')

@section('title', 'Reportes y Estadísticas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-graph-up"></i> Reportes y Estadísticas</h1>
            <p class="text-muted">Análisis del sistema veterinario</p>
        </div>
    </div>

    <!-- Filtro de Fechas -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reportes.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Mascotas</h6>
                    <h2 class="mb-0">{{ $totalMascotas }}</h2>
                    <small class="text-success">{{ $mascotasActivas }} activas</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Propietarios</h6>
                    <h2 class="mb-0">{{ $totalPropietarios }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Veterinarios</h6>
                    <h2 class="mb-0">{{ $totalVeterinarios }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Citas en Período</h6>
                    <h2 class="mb-0">{{ $citasPeriodo }}</h2>
                    <small class="text-success">{{ $citasCompletadas }} completadas</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Ingresos y Citas -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-cash-stack"></i> Ingresos del Período
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4 text-success mb-0">Bs. {{ number_format($ingresosPeriodo, 2) }}</h1>
                    <p class="text-muted">{{ $fechaInicio }} al {{ $fechaFin }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pie-chart"></i> Distribución de Citas
                </div>
                <div class="card-body">
                    <canvas id="citasChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-person-badge"></i> Citas por Veterinario
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Veterinario</th>
                                <th class="text-end">Total Citas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($citasPorVeterinario as $item)
                                <tr>
                                    <td>
                                        @if($item->veterinario)
                                            Dr. {{ $item->veterinario->nombre_completo }}
                                        @else
                                            Sin asignar
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ $item->total }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No hay datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-heart"></i> Mascotas por Especie
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Especie</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mascotasPorEspecie as $item)
                                <tr>
                                    <td>{{ ucfirst($item->especie) }}</td>
                                    <td class="text-end"><strong>{{ $item->total }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('citasChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Completadas', 'Canceladas', 'Otras'],
            datasets: [{
                data: [{{ $citasCompletadas }}, {{ $citasCanceladas }}, {{ $citasPeriodo - $citasCompletadas - $citasCanceladas }}],
                backgroundColor: ['#10b981', '#ef4444', '#6366f1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush