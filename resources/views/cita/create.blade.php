@extends('layouts.app')

@section('title', 'Agendar Cita')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('citas.index') }}">Citas</a></li>
                    <li class="breadcrumb-item active">Agendar Nueva Cita</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0"><i class="bi bi-calendar-plus"></i> Agendar Nueva Cita</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-calendar-check"></i> Datos de la Cita
                </div>
                <div class="card-body">
                    <form action="{{ route('citas.store') }}" method="POST" id="formCita">
                        @csrf

                        <!-- Selección de Propietario -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person"></i> 1. Seleccionar Propietario *
                            </label>
                            <select name="propietario_id" 
                                    id="propietario_id" 
                                    class="form-select @error('propietario_id') is-invalid @enderror" 
                                    required
                                    onchange="cargarMascotas()">
                                <option value="">Seleccione un propietario...</option>
                                @foreach($propietarios as $propietario)
                                    <option value="{{ $propietario->id }}" 
                                            data-mascotas="{{ $propietario->mascotas->pluck('id') }}"
                                            {{ old('propietario_id', $selectedMascota?->propietario_id) == $propietario->id ? 'selected' : '' }}>
                                        {{ $propietario->nombre_completo }} - CI: {{ $propietario->ci }}
                                    </option>
                                @endforeach
                            </select>
                            @error('propietario_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selección de Mascota -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-heart"></i> 2. Seleccionar Mascota *
                            </label>
                            <select name="mascota_id" 
                                    id="mascota_id" 
                                    class="form-select @error('mascota_id') is-invalid @enderror" 
                                    required
                                    disabled>
                                <option value="">Primero seleccione un propietario...</option>
                            </select>
                            @error('mascota_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview de la mascota seleccionada -->
                            <div id="mascotaPreview" class="mt-3" style="display: none;">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <img id="mascotaFoto" 
                                                     src="" 
                                                     class="rounded" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-1" id="mascotaNombre"></h6>
                                                <p class="mb-0">
                                                    <span class="badge bg-primary" id="mascotaEspecie"></span>
                                                    <span class="badge bg-secondary" id="mascotaRaza"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Información de la Cita -->
                        <h5 class="mb-3"><i class="bi bi-calendar-event"></i> 3. Información de la Cita</h5>
                        
                        <div class="row g-3">
                            <!-- Fecha y Hora -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-calendar3"></i> Fecha y Hora *
                                </label>
                                <input type="datetime-local" 
                                       name="fecha_hora" 
                                       class="form-control @error('fecha_hora') is-invalid @enderror" 
                                       value="{{ old('fecha_hora', now()->addHour()->format('Y-m-d\TH:i')) }}"
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
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
                                    <option value="">Asignar después...</option>
                                    @foreach($veterinarios as $veterinario)
                                        <option value="{{ $veterinario->id }}" {{ old('veterinario_id') == $veterinario->id ? 'selected' : '' }}>
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
                                    <i class="bi bi-clipboard-pulse"></i> Motivo de la Consulta *
                                </label>
                                <input type="text" 
                                       name="motivo" 
                                       class="form-control @error('motivo') is-invalid @enderror" 
                                       value="{{ old('motivo') }}"
                                       placeholder="Ej: Vacunación, Control de rutina, Emergencia..."
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
                                    <option value="pendiente" {{ old('estado', 'pendiente') == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente
                                    </option>
                                    <option value="confirmada" {{ old('estado') == 'confirmada' ? 'selected' : '' }}>
                                        Confirmada
                                    </option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notas -->
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-file-text"></i> Notas Adicionales
                                </label>
                                <textarea name="notas" 
                                          class="form-control @error('notas') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="Información adicional, síntomas, comportamiento...">{{ old('notas') }}</textarea>
                                @error('notas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Agendar Cita
                                </button>
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

@push('scripts')
<script>
    // Datos de mascotas en JSON
    const mascotasData = @json($mascotas);

    function cargarMascotas() {
        const propietarioSelect = document.getElementById('propietario_id');
        const mascotaSelect = document.getElementById('mascota_id');
        const propietarioId = propietarioSelect.value;

        // Limpiar select de mascotas
        mascotaSelect.innerHTML = '<option value="">Seleccione una mascota...</option>';
        document.getElementById('mascotaPreview').style.display = 'none';

        if (!propietarioId) {
            mascotaSelect.disabled = true;
            return;
        }

        // Filtrar mascotas del propietario seleccionado
        const mascotasPropietario = mascotasData.filter(m => m.propietario_id == propietarioId);

        if (mascotasPropietario.length === 0) {
            mascotaSelect.innerHTML = '<option value="">No hay mascotas registradas</option>';
            mascotaSelect.disabled = true;
            return;
        }

        // Llenar select
        mascotaSelect.disabled = false;
        mascotasPropietario.forEach(mascota => {
            const option = document.createElement('option');
            option.value = mascota.id;
            option.textContent = `${mascota.nombre} (${mascota.especie})`;
            option.dataset.mascota = JSON.stringify(mascota);
            mascotaSelect.appendChild(option);
        });

        // Si hay mascota seleccionada (por ejemplo, desde URL)
        @if($selectedMascota)
            mascotaSelect.value = {{ $selectedMascota->id }};
            mostrarPreviewMascota();
        @endif
    }

    // Preview de mascota seleccionada
    document.getElementById('mascota_id')?.addEventListener('change', mostrarPreviewMascota);

    function mostrarPreviewMascota() {
        const mascotaSelect = document.getElementById('mascota_id');
        const selectedOption = mascotaSelect.options[mascotaSelect.selectedIndex];
        
        if (!selectedOption.dataset.mascota) {
            document.getElementById('mascotaPreview').style.display = 'none';
            return;
        }

        const mascota = JSON.parse(selectedOption.dataset.mascota);
        
        document.getElementById('mascotaFoto').src = mascota.foto_url;
        document.getElementById('mascotaNombre').textContent = mascota.nombre;
        document.getElementById('mascotaEspecie').textContent = mascota.especie.charAt(0).toUpperCase() + mascota.especie.slice(1);
        document.getElementById('mascotaRaza').textContent = mascota.raza || 'Sin raza';
        document.getElementById('mascotaPreview').style.display = 'block';
    }

    // Cargar mascotas al inicio si hay propietario seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('propietario_id').value) {
            cargarMascotas();
        }
    });
</script>
@endpush