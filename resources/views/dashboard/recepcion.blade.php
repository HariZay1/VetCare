@extends('layouts.app')

@section('title', 'Dashboard Recepción')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-calendar-check"></i> Panel de Recepción</h1>
            <p class="text-muted">Gestión de citas y atención al cliente</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('citas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Cita
            </a>
            <a href="{{ route('propietarios.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Nuevo Cliente
            </a>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-plus fs-1 mb-2"></i>
                    <h6>Agendar Cita</h6>
                    <a href="{{ route('citas.create') }}" class="btn btn-light btn-sm mt-2">
                        Ir <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="bi bi-person-plus fs-1 mb-2"></i>
                    <h6>Registrar Cliente</h6>
                    <a href="{{ route('propietarios.create') }}" class="btn btn-light btn-sm mt-2">
                        Ir <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="bi bi-heart-pulse fs-1 mb-2"></i>
                    <h6>Registrar Mascota</h6>
                    <a href="{{ route('mascotas.create') }}" class="btn btn-light btn-sm mt-2">
                        Ir <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history fs-1 mb-2"></i>
                    <h6>Citas Pendientes</h6>
                    <span class="badge bg-dark fs-5">{{ $citasPendientes->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Citas de Hoy -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calendar-day"></i> Citas de Hoy - {{ now()->format('d/m/Y') }}</span>
                    <div>
                        <a href="{{ route('citas.export.excel') }}" class="btn btn-sm btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                        <a href="{{ route('citas.export.pdf') }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($citasHoy as $cita)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 text-center" style="width: 60px; height: 60px;">
                                    <div class="fw-bold text-primary">{{ $cita->fecha_hora->format('H:i') }}</div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <i class="bi bi-heart text-danger"></i> {{ $cita->mascota->nombre }}
                                    <small class="text-muted">({{ ucfirst($cita->mascota->especie) }})</small>
                                </h6>
                                <p class="mb-1 text-muted">
                                    <i class="bi bi-person"></i> {{ $cita->propietario->nombre_completo }}
                                    <br>
                                    <i class="bi bi-telephone"></i> {{ $cita->propietario->telefono }}
                                </p>
                                <small class="text-muted">
                                    <strong>Motivo:</strong> {{ $cita->motivo }}
                                    @if($cita->veterinario)
                                        | <i class="bi bi-hospital"></i> {{ $cita->veterinario->nombre_completo }}
                                    @endif
                                </small>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <span class="{{ $cita->estado_badge }} d-block mb-2">{{ $cita->estado_texto }}</span>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($cita->estado === 'pendiente')
                                        <form action="{{ route('citas.update', $cita) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="estado" value="confirmada">
                                            <button type="submit" class="btn btn-success" title="Confirmar">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('citas.edit', $cita) }}" class="btn btn-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('citas.pdf', $cita) }}" class="btn btn-danger" title="PDF">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No hay citas programadas para hoy</p>
                            <a href="{{ route('citas.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Agendar Primera Cita
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Derecho -->
        <div class="col-lg-4">
            <!-- Citas Pendientes de Confirmar -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-exclamation-triangle"></i> Pendientes de Confirmar
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($citasPendientes as $cita)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-1">{{ $cita->mascota->nombre }}</h6>
                            <small class="text-muted d-block">
                                <i class="bi bi-person"></i> {{ $cita->propietario->nombre_completo }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-clock"></i> {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                            </small>
                            <form action="{{ route('citas.update', $cita) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="confirmada">
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="bi bi-check-circle"></i> Confirmar
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">No hay citas pendientes</p>
                    @endforelse
                </div>
            </div>

            <!-- Búsqueda Rápida -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-search"></i> Búsqueda Rápida
                </div>
                <div class="card-body">
                    <form action="{{ route('mascotas.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Buscar Mascota</label>
                            <input type="text" name="search" class="form-control" placeholder="Nombre de mascota...">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </form>
                    <hr>
                    <form action="{{ route('propietarios.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Buscar Cliente</label>
                            <input type="text" name="search" class="form-control" placeholder="Nombre o CI...">
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximas Citas (3 días) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-range"></i> Próximas Citas (3 días)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Mascota</th>
                                    <th>Propietario</th>
                                    <th>Veterinario</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proximasCitas as $cita)
                                    <tr>
                                        <td>
                                            <strong>{{ $cita->fecha_hora->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">{{ $cita->fecha_hora->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <i class="bi bi-heart text-danger"></i> {{ $cita->mascota->nombre }}<br>
                                            <small class="text-muted">{{ ucfirst($cita->mascota->especie) }}</small>
                                        </td>
                                        <td>{{ $cita->propietario->nombre_completo }}</td>
                                        <td>
                                            @if($cita->veterinario)
                                                {{ $cita->veterinario->nombre_completo }}
                                            @else
                                                <span class="badge bg-secondary">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($cita->motivo, 30) }}</td>
                                        <td><span class="{{ $cita->estado_badge }}">{{ $cita->estado_texto }}</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('citas.edit', $cita) }}" class="btn btn-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('citas.pdf', $cita) }}" class="btn btn-danger">
                                                    <i class="bi bi-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No hay citas próximas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection