@extends('layouts.app')

@section('title', 'Perfil de ' . $propietario->nombre_completo)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('propietarios.index') }}">Propietarios</a></li>
                    <li class="breadcrumb-item active">{{ $propietario->nombre_completo }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">
                <i class="bi bi-person-circle"></i> Perfil del Propietario
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('propietarios.edit', $propietario) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('mascotas.create') }}?propietario_id={{ $propietario->id }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Agregar Mascota
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información del Propietario -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-person fs-1 text-primary"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $propietario->nombre_completo }}</h4>
                    @if($propietario->user)
                        <span class="badge bg-success mb-3">
                            <i class="bi bi-check-circle-fill"></i> Usuario Activo
                        </span>
                    @endif
                </div>
                <div class="card-body border-top">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted"><i class="bi bi-card-text"></i> CI:</td>
                            <td><strong>{{ $propietario->ci }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-telephone"></i> Teléfono:</td>
                            <td>{{ $propietario->telefono }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted"><i class="bi bi-envelope"></i> Email:</td>
                            <td>{{ $propietario->email }}</td>
                        </tr>
                        @if($propietario->direccion)
                        <tr>
                            <td class="text-muted" style="vertical-align: top;"><i class="bi bi-geo-alt"></i> Dirección:</td>
                            <td>{{ $propietario->direccion }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted"><i class="bi bi-calendar-plus"></i> Registrado:</td>
                            <td>{{ $propietario->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-bar-chart"></i> Estadísticas
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h3 class="text-primary mb-0">{{ $propietario->mascotas->count() }}</h3>
                            <small class="text-muted">Mascotas</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="text-success mb-0">{{ $propietario->citas->count() }}</h3>
                            <small class="text-muted">Citas Totales</small>
                        </div>
                        <div class="col-12">
                            <h3 class="text-info mb-0">
                                {{ $propietario->citas->where('estado', 'completada')->count() }}
                            </h3>
                            <small class="text-muted">Citas Completadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mascotas y Citas -->
        <div class="col-lg-8">
            <!-- Mascotas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-heart-fill text-danger"></i> Mascotas Registradas</span>
                    <a href="{{ route('mascotas.create') }}?propietario_id={{ $propietario->id }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-circle"></i> Agregar Mascota
                    </a>
                </div>
                <div class="card-body">
                    @forelse($propietario->mascotas as $mascota)
                        <div class="card mb-3 border-start border-primary border-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ $mascota->foto_url }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $mascota->nombre }}"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="mb-1">{{ $mascota->nombre }}</h5>
                                        <p class="mb-1">
                                            <span class="badge bg-primary">{{ ucfirst($mascota->especie) }}</span>
                                            @if($mascota->raza)
                                                <span class="badge bg-secondary">{{ $mascota->raza }}</span>
                                            @endif
                                        </p>
                                        @if($mascota->edad)
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3"></i> {{ $mascota->edad }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <a href="{{ route('mascotas.show', $mascota) }}" class="btn btn-sm btn-info mb-1 w-100">
                                            <i class="bi bi-eye"></i> Ver Ficha
                                        </a>
                                        <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-sm btn-primary w-100">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay mascotas registradas para este propietario</p>
                    @endforelse
                </div>
            </div>

            <!-- Historial de Citas -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-calendar-check"></i> Historial de Citas
                </div>
                <div class="card-body">
                    @forelse($propietario->citas()->orderBy('fecha_hora', 'desc')->take(10)->get() as $cita)
                        <div class="card mb-3 border-start {{ $cita->estado === 'completada' ? 'border-success' : 'border-primary' }} border-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h6 class="mb-2">
                                            {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                                            <span class="{{ $cita->estado_badge }} ms-2">{{ $cita->estado_texto }}</span>
                                        </h6>
                                        <p class="mb-1">
                                            <strong>Mascota:</strong> {{ $cita->mascota->nombre }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Motivo:</strong> {{ $cita->motivo }}
                                        </p>
                                        @if($cita->veterinario)
                                            <p class="mb-0 text-muted">
                                                <i class="bi bi-person-badge"></i> Dr. {{ $cita->veterinario->nombre_completo }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        @if($cita->costo)
                                            <p class="mb-2">
                                                <strong>Bs. {{ number_format($cita->costo, 2) }}</strong>
                                            </p>
                                        @endif
                                        <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay citas registradas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection