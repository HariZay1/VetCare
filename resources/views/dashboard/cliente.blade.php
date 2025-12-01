@extends('layouts.app')

@section('title', 'Mis Mascotas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-house-heart"></i> Bienvenido, {{ $propietario->nombre }}</h1>
            <p class="text-muted">Panel de cliente - Gestiona tus mascotas y citas</p>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSolicitarCita">
                <i class="bi bi-calendar-plus"></i> Solicitar Cita
            </button>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-person fs-1 text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $propietario->nombre_completo }}</h5>
                    <p class="text-muted mb-2">{{ $propietario->email }}</p>
                    <p class="mb-0">
                        <i class="bi bi-telephone"></i> {{ $propietario->telefono }}<br>
                        <i class="bi bi-geo-alt"></i> {{ $propietario->direccion ?? 'Sin dirección' }}
                    </p>
                    <hr>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-start border-primary border-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Mis Mascotas</h6>
                            <h2 class="mb-0">{{ $misMascotas->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-start border-success border-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Próximas Citas</h6>
                            <h2 class="mb-0">{{ $proximasCitas->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-start border-info border-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-1">Citas Completadas</h6>
                            <h2 class="mb-0">{{ $historialCitas->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Mascotas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-heart-fill text-danger"></i> Mis Mascotas
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @forelse($misMascotas as $mascota)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ $mascota->foto_url }}" 
                                         class="card-img-top" 
                                         alt="{{ $mascota->nombre }}"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $mascota->nombre }}</h5>
                                        <p class="card-text mb-1">
                                            <i class="bi bi-tag"></i> <strong>{{ ucfirst($mascota->especie) }}</strong>
                                            @if($mascota->raza)
                                                <br><small class="text-muted">{{ $mascota->raza }}</small>
                                            @endif
                                        </p>
                                        @if($mascota->edad)
                                            <p class="card-text mb-1">
                                                <i class="bi bi-calendar3"></i> {{ $mascota->edad }}
                                            </p>
                                        @endif
                                        <p class="card-text">
                                            <i class="bi bi-gender-{{ $mascota->sexo === 'macho' ? 'male' : 'female' }}"></i> 
                                            {{ ucfirst($mascota->sexo ?? 'N/A') }}
                                        </p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <button type="button" 
                                                class="btn btn-primary btn-sm w-100" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalSolicitarCita"
                                                onclick="seleccionarMascota({{ $mascota->id }}, '{{ $mascota->nombre }}')">
                                            <i class="bi bi-calendar-plus"></i> Solicitar Cita
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-heart-pulse fs-1 text-muted"></i>
                                <p class="text-muted mt-3">No tienes mascotas registradas</p>
                                <p class="text-muted">Contacta a la recepción para registrar tu primera mascota</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximas Citas -->
    @if($proximasCitas->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-calendar-check"></i> Próximas Citas
                </div>
                <div class="card-body">
                    @foreach($proximasCitas as $cita)
                        <div class="card mb-3 border-success">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <div class="bg-success bg-opacity-10 rounded p-3">
                                            <h5 class="mb-0 text-success">{{ $cita->fecha_hora->format('d') }}</h5>
                                            <small class="text-muted">{{ $cita->fecha_hora->format('M Y') }}</small>
                                            <p class="mb-0 fw-bold text-success">{{ $cita->fecha_hora->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="mb-2">
                                            <i class="bi bi-heart text-danger"></i> {{ $cita->mascota->nombre }}
                                        </h5>
                                        <p class="mb-1">
                                            <strong>Motivo:</strong> {{ $cita->motivo }}
                                        </p>
                                        @if($cita->veterinario)
                                            <p class="mb-0">
                                                <i class="bi bi-person-badge"></i> Dr. {{ $cita->veterinario->nombre_completo }}
                                                @if($cita->veterinario->especialidad)
                                                    <small class="text-muted">({{ $cita->veterinario->especialidad }})</small>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="{{ $cita->estado_badge }} d-block mb-2">{{ $cita->estado_texto }}</span>
                                        @if($cita->estado === 'completada')
                                            <a href="{{ route('citas.pdf', $cita) }}" class="btn btn-danger btn-sm">
                                                <i class="bi bi-file-pdf"></i> Descargar Receta
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Historial de Citas -->
    @if($historialCitas->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i> Historial de Citas
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Mascota</th>
                                    <th>Veterinario</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historialCitas as $cita)
                                    <tr>
                                        <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                        <td>{{ $cita->mascota->nombre }}</td>
                                        <td>
                                            @if($cita->veterinario)
                                                Dr. {{ $cita->veterinario->nombre_completo }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($cita->motivo, 30) }}</td>
                                        <td><span class="{{ $cita->estado_badge }}">{{ $cita->estado_texto }}</span></td>
                                        <td>
                                            @if($cita->estado === 'completada' && $cita->receta)
                                                <a href="{{ route('citas.pdf', $cita) }}" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-file-pdf"></i> PDF
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Solicitar Cita -->
<div class="modal fade" id="modalSolicitarCita" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('citas.solicitar') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-plus"></i> Solicitar Cita
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="propietario_id" value="{{ $propietario->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Mascota</strong></label>
                        <select name="mascota_id" id="selectMascota" class="form-select" required>
                            <option value="">Seleccione una mascota...</option>
                            @foreach($misMascotas as $mascota)
                                <option value="{{ $mascota->id }}">{{ $mascota->nombre }} ({{ ucfirst($mascota->especie) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Fecha y Hora Deseada</strong></label>
                        <input type="datetime-local" name="fecha_hora" class="form-control" 
                               min="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Motivo de la Consulta</strong></label>
                        <textarea name="motivo" class="form-control" rows="3" required 
                                  placeholder="Describa el motivo de la consulta..."></textarea>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Tu solicitud quedará como <strong>pendiente</strong> hasta que recepción la confirme.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function seleccionarMascota(id, nombre) {
        document.getElementById('selectMascota').value = id;
    }
</script>
@endpush