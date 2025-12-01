@extends('layouts.app')

@section('title', 'Mi Agenda')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-calendar2-week"></i> Mi Agenda Veterinaria</h1>
            <p class="text-muted">Dr. {{ $veterinario->nombre_completo }} | {{ $veterinario->especialidad ?? 'Veterinario General' }}</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-day"></i> Hoy
                </button>
                <button type="button" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-week"></i> Semana
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas del Día -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-check fs-2 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0">Citas de Hoy</h6>
                            <h2 class="mb-0">{{ $citasHoy->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle fs-2 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0">Completadas</h6>
                            <h2 class="mb-0">{{ $citasCompletadasHoy }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-clock-history fs-2 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0">Pendientes</h6>
                            <h2 class="mb-0">{{ $citasHoy->whereIn('estado', ['pendiente', 'confirmada'])->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agenda del Día -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-day"></i> Agenda de Hoy - {{ now()->format('d/m/Y') }}
                </div>
                <div class="card-body">
                    @forelse($citasHoy as $cita)
                        <div class="card mb-3 {{ $cita->estado === 'completada' ? 'border-success' : 'border-primary' }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <div class="bg-primary bg-opacity-10 rounded p-3">
                                            <h3 class="mb-0 text-primary">{{ $cita->fecha_hora->format('H:i') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="mb-2">
                                            <i class="bi bi-heart-pulse text-danger"></i> {{ $cita->mascota->nombre }}
                                            <small class="text-muted">({{ ucfirst($cita->mascota->especie) }} - {{ $cita->mascota->raza }})</small>
                                        </h5>
                                        <p class="mb-1">
                                            <i class="bi bi-person"></i> <strong>Propietario:</strong> {{ $cita->propietario->nombre_completo }}
                                            <br>
                                            <i class="bi bi-telephone"></i> {{ $cita->propietario->telefono }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="bi bi-clipboard-pulse"></i> <strong>Motivo:</strong> {{ $cita->motivo }}
                                        </p>
                                        @if($cita->mascota->edad)
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3"></i> Edad: {{ $cita->mascota->edad }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="{{ $cita->estado_badge }} d-block mb-2">{{ $cita->estado_texto }}</span>
                                        
                                        @if($cita->estado !== 'completada')
                                            <button type="button" class="btn btn-success btn-sm w-100 mb-2"
                                                onclick="openAtenderModal(this)"
                                                data-cita-id="{{ $cita->id }}"
                                                data-mascota-name="{{ $cita->mascota->nombre }}"
                                                data-propietario-name="{{ $cita->propietario->nombre_completo }}"
                                                data-motivo="{{ e($cita->motivo) }}"
                                                data-costo="{{ $cita->costo }}"
                                                data-diagnostico="{{ e($cita->diagnostico) }}"
                                            >
                                                <i class="bi bi-clipboard-check"></i> Atender
                                            </button>
                                        @endif

                                        <a href="{{ route('veterinario.citas.show', $cita) }}" class="btn btn-primary btn-sm w-100 mb-2">
                                            <i class="bi bi-eye"></i> Ver Ficha
                                        </a>
                                        
                                        @if($cita->estado === 'completada')
                                            <a href="{{ route('citas.pdf', $cita) }}" class="btn btn-danger btn-sm w-100">
                                                <i class="bi bi-file-pdf"></i> Receta PDF
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if($cita->notas)
                                    <div class="alert alert-info mt-3 mb-0">
                                        <strong><i class="bi bi-info-circle"></i> Notas:</strong> {{ $cita->notas }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Modal placeholder: one modal used for all citas (populated via JS) -->
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No tienes citas programadas para hoy</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Derecho -->
        <div class="col-lg-4">
            <!-- Próximas Citas -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-calendar-event"></i> Próximas Citas
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($proximasCitas as $cita)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $cita->mascota->nombre }}</h6>

                                <!-- Single modal for attending citas (dynamic) -->
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-clock"></i> {{ $cita->fecha_hora->format('d/m/Y H:i') }}
                                    </small>
                                    <small class="text-muted d-block">
                                        {{ Str::limit($cita->motivo, 40) }}
                                    </small>
                                </div>
                                <span class="{{ $cita->estado_badge }}">{{ $cita->estado_texto }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">No hay próximas citas</p>
                    @endforelse
                </div>
            </div>

            <!-- Historial Rápido -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i> Acceso Rápido
                </div>
                <div class="card-body">
                    <a href="{{ route('tratamientos.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-clipboard-pulse"></i> Historial de Tratamientos
                    </a>
                    <a href="{{ route('veterinario.citas') }}" class="btn btn-outline-success w-100 mb-2">
                        <i class="bi bi-calendar-check"></i> Todas Mis Citas
                    </a>
                    <a href="{{ route('mascotas.index') }}" class="btn btn-outline-info w-100">
                        <i class="bi bi-search"></i> Buscar Paciente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Single modal for attending citas (dynamic) placed after content -->
<div class="modal fade" id="modalAtenderVet" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formAtenderVet" action="#" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAtenderTitle">
                        <i class="bi bi-clipboard-check"></i> Atender Cita
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" id="modalAtenderInfo">
                        <!-- Filled by JS -->
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Diagnóstico *</strong></label>
                        <textarea name="diagnostico" id="modalDiagnostico" class="form-control" rows="4" required placeholder="Ingrese el diagnóstico..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Receta/Tratamiento</strong></label>
                        <textarea name="receta" id="modalReceta" class="form-control" rows="4" placeholder="Medicamentos, dosis, indicaciones..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Costo (Bs.)</strong></label>
                            <input type="number" name="costo" id="modalCosto" class="form-control" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Fecha de Seguimiento</strong></label>
                            <input type="date" name="fecha_seguimiento" id="modalFechaSeguimiento" class="form-control" min="{{ now()->addDays(1)->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Completar Atención
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAtenderModal(btn) {
        const citaId = btn.getAttribute('data-cita-id');
        const mascota = btn.getAttribute('data-mascota-name');
        const propietario = btn.getAttribute('data-propietario-name');
        const motivo = btn.getAttribute('data-motivo');
        const costo = btn.getAttribute('data-costo') || '';
        const diagnostico = btn.getAttribute('data-diagnostico') || '';

        const form = document.getElementById('formAtenderVet');
        form.action = '/citas/' + citaId + '/completar';

        document.getElementById('modalAtenderTitle').innerText = 'Atender Cita - ' + mascota;
        document.getElementById('modalAtenderInfo').innerHTML = '<strong>Mascota:</strong> ' + mascota + '<br><strong>Propietario:</strong> ' + propietario + '<br><strong>Motivo:</strong> ' + motivo;
        document.getElementById('modalCosto').value = costo;
        document.getElementById('modalDiagnostico').value = diagnostico;
        document.getElementById('modalReceta').value = '';
        document.getElementById('modalFechaSeguimiento').value = '';

        const modalEl = document.getElementById('modalAtenderVet');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
</script>
@endpush

@endsection