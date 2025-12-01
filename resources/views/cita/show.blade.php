@extends('layouts.app')

@section('title', 'Detalle de Cita')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('citas.index') }}">Citas</a></li>
                    <li class="breadcrumb-item active">Detalle de Cita</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">
                <i class="bi bi-calendar-event"></i> Detalle de Cita
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('citas.edit', $cita) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            @if($cita->estado === 'completada')
                <a href="{{ route('citas.pdf', $cita) }}" class="btn btn-danger">
                    <i class="bi bi-file-pdf"></i> Generar PDF
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <!-- Datos de la Cita -->
            <div class="card shadow-sm mb-4">
                <div class="card-header {{ 
                    $cita->estado === 'completada' ? 'bg-success text-white' : 
                    ($cita->estado === 'cancelada' ? 'bg-danger text-white' : 'bg-primary text-white')
                }}">
                    <i class="bi bi-calendar-check"></i> Información de la Cita
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5><i class="bi bi-calendar3"></i> Fecha y Hora</h5>
                            <p class="fs-4 mb-0">{{ $cita->fecha_hora->format('d/m/Y') }}</p>
                            <p class="text-muted">{{ $cita->fecha_hora->format('H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h5>Estado</h5>
                            <span class="{{ $cita->estado_badge }} fs-5">
                                {{ $cita->estado_texto }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Motivo de Consulta</h6>
                            <p>{{ $cita->motivo }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($cita->veterinario)
                                <h6 class="text-muted mb-2">Veterinario Asignado</h6>
                                <p>
                                    <i class="bi bi-person-badge text-primary"></i> 
                                    Dr. {{ $cita->veterinario->nombre_completo }}
                                    @if($cita->veterinario->especialidad)
                                        <br><small class="text-muted">{{ $cita->veterinario->especialidad }}</small>
                                    @endif
                                </p>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Sin veterinario asignado
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($cita->notas)
                        <div class="alert alert-info mt-3">
                            <strong><i class="bi bi-info-circle"></i> Notas:</strong>
                            <p class="mb-0 mt-2">{{ $cita->notas }}</p>
                        </div>
                    @endif

                    @if($cita->costo)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card bg-success bg-opacity-10 border-success">
                                    <div class="card-body text-center">
                                        <h6 class="text-success mb-1">Costo de la Consulta</h6>
                                        <h2 class="text-success mb-0">Bs. {{ number_format($cita->costo, 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Diagnóstico y Tratamiento (si está completada) -->
            @if($cita->diagnostico || $cita->receta)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-clipboard-check"></i> Diagnóstico y Tratamiento
                    </div>
                    <div class="card-body">
                        @if($cita->diagnostico)
                            <h6 class="mb-2"><strong>Diagnóstico:</strong></h6>
                            <div class="alert alert-success">
                                {{ $cita->diagnostico }}
                            </div>
                        @endif

                        @if($cita->receta)
                            <h6 class="mb-2"><strong>Receta/Tratamiento:</strong></h6>
                            <div class="alert alert-info">
                                {{ $cita->receta }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Tratamientos Adicionales -->
            @if($cita->tratamientos->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header">
                        <i class="bi bi-clipboard-pulse"></i> Tratamientos Registrados
                    </div>
                    <div class="card-body">
                        @foreach($cita->tratamientos as $tratamiento)
                            <div class="card mb-3 border-start border-info border-4">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Descripción:</strong> {{ $tratamiento->descripcion }}</p>
                                    @if($tratamiento->receta)
                                        <p class="mb-1"><strong>Receta:</strong> {{ $tratamiento->receta }}</p>
                                    @endif
                                    @if($tratamiento->fecha_seguimiento)
                                        <p class="mb-0">
                                            <strong>Seguimiento:</strong> 
                                            <span class="badge bg-warning">
                                                {{ $tratamiento->fecha_seguimiento->format('d/m/Y') }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Derecho -->
        <div class="col-lg-4">
            <!-- Información de la Mascota -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-heart-fill"></i> Mascota
                </div>
                <div class="card-body text-center">
                    <img src="{{ $cita->mascota->foto_url }}" 
                         class="rounded shadow-sm mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mb-1">{{ $cita->mascota->nombre }}</h5>
                    <p class="mb-2">
                        <span class="badge bg-primary">{{ ucfirst($cita->mascota->especie) }}</span>
                        @if($cita->mascota->raza)
                            <span class="badge bg-secondary">{{ $cita->mascota->raza }}</span>
                        @endif
                    </p>
                    @if($cita->mascota->edad)
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar3"></i> {{ $cita->mascota->edad }}
                        </p>
                    @endif
                    <hr>
                    <a href="{{ route('mascotas.show', $cita->mascota) }}" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-eye"></i> Ver Ficha Completa
                    </a>
                </div>
            </div>

            <!-- Información del Propietario -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-person"></i> Propietario
                </div>
                <div class="card-body">
                    <h5 class="mb-3">{{ $cita->propietario->nombre_completo }}</h5>
                    <p class="mb-1">
                        <i class="bi bi-card-text"></i> CI: {{ $cita->propietario->ci }}
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-telephone"></i> {{ $cita->propietario->telefono }}
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-envelope"></i> {{ $cita->propietario->email }}
                    </p>
                    @if($cita->propietario->direccion)
                        <p class="mb-0">
                            <i class="bi bi-geo-alt"></i> {{ $cita->propietario->direccion }}
                        </p>
                    @endif
                    <hr>
                    <a href="{{ route('propietarios.show', $cita->propietario) }}" class="btn btn-sm btn-info w-100">
                        <i class="bi bi-eye"></i> Ver Perfil Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection