@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('citas.index') }}">Citas</a></li>
                    <li class="breadcrumb-item active">Editar Cita</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0"><i class="bi bi-pencil"></i> Editar Cita</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-calendar-check"></i> Datos de la Cita
                </div>
                <div class="card-body">
                    <form action="{{ route('citas.update', $cita) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Info de la Mascota (readonly) -->
                        <div class="card border-primary mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ $cita->mascota->foto_url }}" 
                                             class="rounded" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-1">{{ $cita->mascota->nombre }}</h5>
                                        <p class="mb-1">
                                            <strong>Propietario:</strong> {{ $cita->propietario->nombre_completo }}<br>
                                            <strong>Especie:</strong> {{ ucfirst($cita->mascota->especie) }}
                                            @if($cita->mascota->raza)
                                                | <strong>Raza:</strong> {{ $cita->mascota->raza }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos hidden -->
                        <input type="hidden" name="mascota_id" value="{{ $cita->mascota_id }}">
                        <input type="hidden" name="propietario_id" value="{{ $cita->propietario_id }}">

                        <div class="row g-3">
                            <!-- Fecha y Hora -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-calendar3"></i> Fecha y Hora *
                                </label>
                                <input type="datetime-local" 
                                       name="fecha_hora" 
                                       class="form-control @error('fecha_hora') is-invalid @enderror" 
                                       value="{{ old('fecha_hora', $cita->fecha_hora->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('fecha_hora')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Veterinario -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-person-badge"></i> Veterinario
                                </label>
                                <select name="veterinario_id" class="form-select @error('veterinario_id') is-invalid @enderror">
                                    <option value="">Sin asignar</option>
                                    @foreach($veterinarios as $veterinario)
                                        <option value="{{ $veterinario->id }}" 
                                                {{ old('veterinario_id', $cita->veterinario_id) == $veterinario->id ? 'selected' : '' }}>
                                            {{ $veterinario->nombre_completo }}
                                            @if($veterinario->especialidad)
                                                - {{ $veterinario->especialidad }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('veterinario_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Motivo -->
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-clipboard-pulse"></i> Motivo *
                                </label>
                                <input type="text" 
                                       name="motivo" 
                                       class="form-control @error('motivo') is-invalid @enderror" 
                                       value="{{ old('motivo', $cita->motivo) }}"
                                       required>
                                @error('motivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-flag"></i> Estado *
                                </label>
                                <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                    <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente
                                    </option>
                                    <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>
                                        Confirmada
                                    </option>
                                    <option value="en_proceso" {{ old('estado', $cita->estado) == 'en_proceso' ? 'selected' : '' }}>
                                        En Proceso
                                    </option>
                                    <option value="completada" {{ old('estado', $cita->estado) == 'completada' ? 'selected' : '' }}>
                                        Completada
                                    </option>
                                    <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>
                                        Cancelada
                                    </option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Costo -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-cash"></i> Costo (Bs.)
                                </label>
                                <input type="number" 
                                       name="costo" 
                                       class="form-control @error('costo') is-invalid @enderror" 
                                       value="{{ old('costo', $cita->costo) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                                @error('costo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notas -->
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-file-text"></i> Notas
                                </label>
                                <textarea name="notas" 
                                          class="form-control @error('notas') is-invalid @enderror" 
                                          rows="2">{{ old('notas', $cita->notas) }}</textarea>
                                @error('notas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Diagnóstico (si está completada) -->
                            @if($cita->estado === 'completada' || old('estado') === 'completada')
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-clipboard-check"></i> Diagnóstico
                                    </label>
                                    <textarea name="diagnostico" 
                                              class="form-control @error('diagnostico') is-invalid @enderror" 
                                              rows="3">{{ old('diagnostico', $cita->diagnostico) }}</textarea>
                                    @error('diagnostico')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Receta -->
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-prescription"></i> Receta/Tratamiento
                                    </label>
                                    <textarea name="receta" 
                                              class="form-control @error('receta') is-invalid @enderror" 
                                              rows="3">{{ old('receta', $cita->receta) }}</textarea>
                                    @error('receta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('citas.show', $cita) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> Ver Detalles
                                </a>
                                <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection