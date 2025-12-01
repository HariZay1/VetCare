@extends('layouts.app')

@section('title', 'Ficha de ' . $mascota->nombre)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mascotas.index') }}">Mascotas</a></li>
                    <li class="breadcrumb-item active">{{ $mascota->nombre }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">
                <i class="bi bi-heart-fill text-danger"></i> Ficha Médica: {{ $mascota->nombre }}
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('citas.create') }}?mascota_id={{ $mascota->id }}" class="btn btn-success">
                <i class="bi bi-calendar-plus"></i> Nueva Cita
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información General -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <img src="{{ $mascota->foto_url }}" 
                     class="card-img-top" 
                     alt="{{ $mascota->nombre }}"
                     style="height: 300px; object-fit: cover;">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ $mascota->nombre }}</h4>
                    
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted"><i class="bi bi-tag"></i> Especie:</td>
                            <td><strong>{{ ucfirst($mascota->especie) }}</strong></td>
                        </tr>
                        @if($mascota->raza)
                        <tr>
                            <td class="text-muted"><i class="bi bi-bookmark"></i> Raza:</td>
                            <td>{{ $mascota->raza }}</td>
                        </tr>
                        @endif
                        @if($mascota->sexo)
                        <tr>
                            <td class="text-muted"><i class="bi bi-gender-{{ $mascota->sexo === 'macho' ? 'male' : 'female' }}"></i> Sexo:</td>
                            <td>{{ ucfirst($mascota->sexo) }}</td>
                        </tr>
                        @endif
                        @if($mascota->color)
                        <tr>
                            <td class="text-muted"><i class="bi bi-palette"></i> Color:</td>
                            <td>{{ $mascota->color }}</td>
                        </tr>
                        @endif
                        @if($mascota->edad)
                        <tr>
                            <td class="text-muted"><i class="bi bi-calendar3"></i> Edad:</td>
                            <td>{{ $mascota->edad }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted"><i class="bi bi-toggle-on"></i> Estado:</td>
                            <td>
                                <span class="badge {{ $mascota->activo ? 'bg-success' : 'bg-danger' }}">
                                    {{ $mascota->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    @if($mascota->notas)
                    <div class="alert alert-info mt-3">
                        <strong><i class="bi bi-file-text"></i> Notas:</strong>
                        <p class="mb-0 mt-2">{{ $mascota->notas }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Datos del Propietario -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-person"></i> Propietario
                </div>
                <div class="card-body">
                    <h5>{{ $mascota->propietario->nombre_completo }}</h5>
                    <p class="mb-1">
                        <i class="bi bi-card-text"></i> CI: {{ $mascota->propietario->ci }}
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-telephone"></i> {{ $mascota->propietario->telefono }}
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-envelope"></i> {{ $mascota->propietario->email }}
                    </p>
                    @if($mascota->propietario->direccion)
                    <p class="mb-0">
                        <i class="bi bi-geo-alt"></i> {{ $mascota->propietario->direccion }}
                    </p>
                    @endif
                    <hr>
                    <a href="{{ route('propietarios.show', $mascota->propietario) }}" class="btn btn-sm btn-success w-100">
                        <i class="bi bi-eye"></i> Ver Perfil Completo
                    </a>
                </div>
            </div>
        </div>

        <!-- Historial Médico -->
        <div class="col-lg-8">
            <!-- Citas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i class="bi bi-calendar-check"></i> Historial de Citas
                </div>
                <div class="card-body">
                    @forelse($mascota->citas()->orderBy('fecha_hora', 'desc')->take(10)->get() as $cita)
                        <div class="card mb-3 border-start {{ $cita->estado === 'completada' ? 'border-success border-4' : 'border-primary border-4' }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h6 class="mb-2">
                                            {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                                            <span class="{{ $cita->estado_badge }} ms-2">{{ $cita->estado_texto }}</span>
                                        </h6>
                                        <p class="mb-1">
                                            <strong>Motivo:</strong> {{ $cita->motivo }}
                                        </p>
                                        @if($cita->veterinario)
                                            <p class="mb-1 text-muted">
                                                <i class="bi bi-person-badge"></i> Dr. {{ $cita->veterinario->nombre_completo }}
                                            </p>
                                        @endif

                                        @if($cita->diagnostico)
                                            <div class="alert alert-success mt-2 mb-2">
                                                <strong>Diagnóstico:</strong> {{ $cita->diagnostico }}
                                            </div>
                                        @endif

                                        @if($cita->receta)
                                            <div class="alert alert-info mt-2 mb-0">
                                                <strong>Receta:</strong> {{ $cita->receta }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        @if($cita->costo)
                                            <p class="mb-2">
                                                <strong>Costo:</strong><br>
                                                <span class="fs-5 text-success">Bs. {{ number_format($cita->costo, 2) }}</span>
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

                    @if($mascota->citas->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('citas.index') }}?mascota_id={{ $mascota->id }}" class="btn btn-outline-primary">
                                Ver Todas las Citas ({{ $mascota->citas->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tratamientos -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-clipboard-pulse"></i> Tratamientos Registrados
                </div>
                <div class="card-body">
                    @forelse($mascota->tratamientos()->orderBy('created_at', 'desc')->take(5)->get() as $tratamiento)
                        <div class="card mb-3 border-start border-info border-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $tratamiento->created_at->format('d/m/Y') }}</h6>
                                        <p class="mb-2"><strong>Descripción:</strong> {{ $tratamiento->descripcion }}</p>
                                        @if($tratamiento->receta)
                                            <p class="mb-1 text-muted"><strong>Receta:</strong> {{ $tratamiento->receta }}</p>
                                        @endif
                                        @if($tratamiento->fecha_seguimiento)
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-event"></i> Seguimiento: {{ $tratamiento->fecha_seguimiento->format('d/m/Y') }}
                                            </small>
                                        @endif
                                    </div>
                                    @if($tratamiento->costo > 0)
                                        <span class="badge bg-success fs-6">Bs. {{ number_format($tratamiento->costo, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay tratamientos registrados</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection