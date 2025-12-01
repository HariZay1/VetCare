@extends('layouts.app')

@section('template_title')
    {{ $tratamiento->name ?? __('Show') . " " . __('Tratamiento') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Tratamiento</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('tratamientos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                                <div class="form-group mb-2 mb20">
                                    <strong>Cita (Motivo):</strong>
                                    {{ optional($tratamiento->cita)->motivo ?? '-' }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Mascota:</strong>
                                    {{ optional($tratamiento->mascota)->nombre ?? '-' }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Propietario:</strong>
                                    {{ optional(optional($tratamiento->cita)->propietario)->nombre_completo ?? optional(optional($tratamiento->mascota)->propietario)->nombre_completo ?? '-' }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Teléfono Propietario:</strong>
                                    {{ optional(optional($tratamiento->cita)->propietario)->telefono ?? optional(optional($tratamiento->mascota)->propietario)->telefono ?? '-' }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $tratamiento->descripcion }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Receta:</strong>
                                    {{ $tratamiento->receta }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Costo:</strong>
                                    {{ $tratamiento->costo }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Seguimiento:</strong>
                                    {{ $tratamiento->fecha_seguimiento }}
                                </div>

                                @if($tratamiento->cita && $tratamiento->cita->estado !== 'completada')
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-success" onclick="event.preventDefault(); bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAtenderTrat{{ $tratamiento->id }}')).show();">
                                            <i class="bi bi-clipboard-check"></i> Atender
                                        </button>
                                    </div>

                                    <!-- Modal Atender desde Tratamiento -->
                                    <div class="modal fade" id="modalAtenderTrat{{ $tratamiento->id }}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('citas.completar', $tratamiento->cita) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-clipboard-check"></i> Atender Cita - {{ optional($tratamiento->mascota)->nombre }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            <strong>Mascota:</strong> {{ optional($tratamiento->mascota)->nombre }} ({{ optional($tratamiento->mascota)->especie ?? '' }})<br>
                                                            <strong>Propietario:</strong> {{ optional(optional($tratamiento->cita)->propietario)->nombre_completo ?? '-' }}<br>
                                                            <strong>Motivo:</strong> {{ optional($tratamiento->cita)->motivo ?? '-' }}
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Diagnóstico *</strong></label>
                                                            <textarea name="diagnostico" class="form-control" rows="4" required placeholder="Ingrese el diagnóstico..."></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label"><strong>Receta/Tratamiento</strong></label>
                                                            <textarea name="receta" class="form-control" rows="4" placeholder="Medicamentos, dosis, indicaciones..."></textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label"><strong>Costo (Bs.)</strong></label>
                                                                <input type="number" name="costo" class="form-control" step="0.01" min="0" placeholder="0.00">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><strong>Fecha de Seguimiento</strong></label>
                                                                <input type="date" name="fecha_seguimiento" class="form-control" min="{{ now()->addDays(1)->format('Y-m-d') }}">
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
                                @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
