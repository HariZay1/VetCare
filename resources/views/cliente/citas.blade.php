@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-calendar-check"></i> Mis Citas</h1>
            <p class="text-muted">Consultas y citas de tus mascotas</p>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSolicitarCita">
                <i class="bi bi-calendar-plus"></i> Solicitar Cita
            </button>
        </div>
    </div>

    <!-- Próximas Citas -->
    @if($proximasCitas->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <i class="bi bi-calendar-event"></i> Próximas Citas
        </div>
        <div class="card-body">
            @foreach($proximasCitas as $cita)
                <div class="card mb-3 border-start border-success border-4">
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
                                @if($cita->estado === 'completada' && $cita->receta)
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
    @endif

    <!-- Historial de Citas -->
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="bi bi-clock-history"></i> Historial de Citas
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
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
                        @forelse($historialCitas as $cita)
                            <tr>
                                <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                <td>
                                    <i class="bi bi-heart text-danger"></i> {{ $cita->mascota->nombre }}
                                </td>
                                <td>
                                    @if($cita->veterinario)
                                        Dr. {{ $cita->veterinario->nombre_completo }}
                                    @else
                                        <span class="badge bg-secondary">Sin asignar</span>
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No tienes historial de citas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($historialCitas->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $historialCitas->links() }}
                </div>
            @endif
        </div>
    </div>
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
                            @foreach($propietario->mascotas as $mascota)
                                <option value="{{ $mascota->id }}">{{ $mascota->nombre }} ({{ ucfirst($mascota->especie) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Fecha y Hora Deseada</strong></label>
                        <input type="datetime-local" 
                               name="fecha_hora" 
                               class="form-control" 
                               min="{{ now()->format('Y-m-d\TH:i') }}" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Motivo de la Consulta</strong></label>
                        <textarea name="motivo" 
                                  class="form-control" 
                                  rows="3" 
                                  required 
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