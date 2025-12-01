@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="bi bi-speedometer2"></i> Dashboard Administrativo</h1>
            <p class="text-muted">Bienvenido, {{ auth()->user()->name }}</p>
        </div>
    </div>

    <!-- KPIs Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-heart fs-2 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Mascotas</h6>
                            <h2 class="mb-0">{{ $totalMascotas }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="bi bi-people fs-2 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Propietarios</h6>
                            <h2 class="mb-0">{{ $totalPropietarios }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="bi bi-calendar-check fs-2 text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Citas Hoy</h6>
                            <h2 class="mb-0">{{ $citasHoy }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-clock-history fs-2 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pendientes</h6>
                            <h2 class="mb-0">{{ $citasPendientes }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Gráfico de Citas -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-graph-up"></i> Citas - Últimos 7 Días
                </div>
                <div class="card-body">
                    <canvas id="citasChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico por Estado -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-pie-chart"></i> Citas por Estado
                </div>
                <div class="card-body">
                    <canvas id="estadosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Ingresos y Próximas Citas -->
    <div class="row g-4">
        <!-- Ingresos del Mes -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-cash-stack"></i> Ingresos del Mes
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4 text-success mb-0">Bs. {{ number_format($ingresosMes, 2) }}</h1>
                    <p class="text-muted">{{ now()->format('F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Próximas Citas -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calendar-event"></i> Próximas Citas</span>
                    <a href="{{ route('citas.index') }}" class="btn btn-sm btn-light">Ver Todas</a>
                </div>
                <div class="card-body">
                    @forelse($proximasCitas as $cita)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                    <i class="bi bi-calendar3 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $cita->mascota->nombre }} - {{ $cita->propietario->nombre_completo }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                                    @if($cita->veterinario)
                                        | <i class="bi bi-person"></i> {{ $cita->veterinario->nombre_completo }}
                                    @endif
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="{{ $cita->estado_badge }}">{{ $cita->estado_texto }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">No hay citas próximas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Gráfico de Citas por Día
    const citasData = @json($citasPorDia);
    const fechas = citasData.map(item => {
        const fecha = new Date(item.fecha);
        return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
    });
    const totales = citasData.map(item => item.total);
    
    const citasChart = new Chart(document.getElementById('citasChart'), {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Citas',
                data: totales,
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfico de Citas por Estado
    const estadosData = @json($citasPorEstado);
    const estados = estadosData.map(item => {
        const nombres = {
            'pendiente': 'Pendiente',
            'confirmada': 'Confirmada',
            'en_proceso': 'En Proceso',
            'completada': 'Completada',
            'cancelada': 'Cancelada'
        };
        return nombres[item.estado] || item.estado;
    });
    const cantidades = estadosData.map(item => item.total);
    
    const estadosChart = new Chart(document.getElementById('estadosChart'), {
        type: 'doughnut',
        data: {
            labels: estados,
            datasets: [{
                data: cantidades,
                backgroundColor: [
                    'rgb(245, 158, 11)',
                    'rgb(59, 130, 246)',
                    'rgb(79, 70, 229)',
                    'rgb(16, 185, 129)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush