@extends('layouts.app')

@section('title', 'Perfil de ' . $veterinario->nombre_completo)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('veterinarios.index') }}">Veterinarios</a></li>
                    <li class="breadcrumb-item active">{{ $veterinario->nombre_completo }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">
                <i class="bi bi-person-badge"></i> Perfil del Veterinario
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('veterinarios.edit', $veterinario) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información del Veterinario -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-person-badge fs-1 text-primary"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">Dr. {{ $veterinario->nombre_completo }}</h4>
                    
                    @if($veterinario->especialidad)
                        <p class="text-primary mb-3">
                            <i class="bi bi-award"></i> {{ $veterinario->especialidad }}
                        </p>
                    @endif

                    <span class="badge {{ $veterinario->activo ? 'bg-success' : 'bg-danger' }} mb-3">
                        {{ $veterinario->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="card-body border-top">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted"><i class="bi bi-telephone"></i> Teléfono:</td>
                            <td><strong>{{ $veterinario->telefono }}</strong></td>
                        </tr>
                        @if($veterinario->user)
                            <tr>
                                <td class="text-muted"><i class="bi bi-envelope"></i> Email:</td>
                                <td>{{ $veterinario->user->email }}</td>
                            </tr>
                        @endif
                        @if($veterinario->horario)
                            <tr>
                                <td class="text-muted" style="vertical-align: top;">
                                    <i class="bi bi-clock"></i> Horario:
                                </td>
                                <td>{{ $veterinario->horario }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted"><i class="bi bi-calendar-plus"></i> Registrado:</td>
                            <td>{{ $veterinario->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>

                    @if($veterinario->user)
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="bi bi-check-circle-fill"></i> Usuario del sistema activo
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-bar-chart"></i> Estadísticas
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <h3 class="text-info mb-0">{{ $citasHoy }}</h3>
                            <small class="text-muted">Citas Hoy</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-primary mb-0">{{ $citasSemana }}</h3>
                            <small class="text-muted">Esta Semana</small>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-success mb-0">{{ $citasCompletadas }}</h3>
                            <small class="text-muted">Completadas</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-warning mb-0">{{ $veterinario->citas->count() }}</h3>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agenda y Citas -->
        <div class="col-lg-8">
            <!-- Próximas Citas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calendar-event"></i> Próximas Citas</span>
                    <a href="{{ route('citas.index') }}?veterinario_id={{ $veterinario->id }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i> Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @forelse($proximasCitas as $cita)
                        <div class="card mb-3 border-start border-primary border-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <div class="bg-primary bg-opacity-10 rounded p-2">
                                            <h5 class="mb-0 text-primary">{{ $cita->fecha_hora->format('d') }}</h5>
                                            <small class="text-muted">{{ $cita->fecha_hora->format('M') }}</small>
                                            <p class="mb-0 fw-bold text-primary">{{ $cita->fecha_hora->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <h6 class="mb-1">
                                            <i class="bi bi-heart text-danger"></i> {{ $cita->mascota->nombre }}
                                        </h6>
                                        <p class="mb-1">
                                            <strong>Propietario:</strong> {{ $cita->mascota->propietario->nombre_completo }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Motivo:</strong> {{ $cita->motivo }}
                                        </p>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="{{ $cita->estado_badge }} d-block mb-2">
                                            {{ $cita->estado_texto }}
                                        </span>
                                        <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay citas próximas programadas</p>
                    @endforelse
                </div>
            </div>

            <!-- Historial Reciente -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i> Historial Reciente
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Mascota</th>
                                    <th>Propietario</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($veterinario->citas()->orderBy('fecha_hora', 'desc')->take(10)->get() as $cita)
                                    <tr>
                                        <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                        <td>{{ $cita->mascota->nombre }}</td>
                                        <td>{{ $cita->mascota->propietario->nombre_completo }}</td>
                                        <td>
                                            <span class="{{ $cita->estado_badge }}">{{ $cita->estado_texto }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No hay citas registradas</td>
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