@extends('layouts.app')

@section('title', 'Registrar Mascota')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ auth()->user()->hasRole('cliente') ? route('cliente.mascotas') : route('mascotas.index') }}">Mascotas</a>
                    </li>
                    <li class="breadcrumb-item active">Registrar Nueva Mascota</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0"><i class="bi bi-plus-circle"></i> Registrar Nueva Mascota</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-heart-pulse"></i> Datos de la Mascota
                </div>
                <div class="card-body">
                    <form action="{{ route('mascotas.store') }}" method="POST" enctype="multipart/form-data" id="formMascota">
                        @csrf

                        <!-- Upload de Foto -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <label class="form-label fw-bold">Fotografía de la Mascota</label>
                                <div class="mb-3">
                                    <img id="preview" 
                                         src="{{ asset('images/default-pet.png') }}" 
                                         class="rounded shadow-sm"
                                         style="max-width: 250px; max-height: 250px; object-fit: cover;">
                                </div>
                                <input type="file" 
                                       class="form-control @error('foto') is-invalid @enderror" 
                                       id="foto" 
                                       name="foto" 
                                       accept="image/*"
                                       onchange="previewImage(event)">
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle"></i> Formatos: JPG, PNG, GIF. Máximo 2MB
                                </small>
                                @error('foto')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Información Básica -->
                        <div class="row g-3">
                            <!-- Propietario -->
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-person"></i> Propietario *</label>
                                @if(auth()->user()->hasRole('cliente'))
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $propietarioCliente->nombre_completo }}" 
                                           disabled>
                                    <input type="hidden" name="propietario_id" value="{{ $propietarioCliente->id }}">
                                @else
                                    <select name="propietario_id" class="form-select @error('propietario_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un propietario...</option>
                                        @foreach($propietarios as $propietario)
                                            <option value="{{ $propietario->id }}" {{ old('propietario_id') == $propietario->id ? 'selected' : '' }}>
                                                {{ $propietario->nombre_completo }} - CI: {{ $propietario->ci }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('propietario_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <a href="{{ route('propietarios.create') }}" target="_blank">
                                            <i class="bi bi-plus-circle"></i> Registrar nuevo propietario
                                        </a>
                                    </small>
                                @endif
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-tag"></i> Nombre de la Mascota *</label>
                                <input type="text" 
                                       name="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej: Max, Luna, Bobby..."
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Especie -->
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-list-ul"></i> Especie *</label>
                                <select name="especie" class="form-select @error('especie') is-invalid @enderror" required>
                                    <option value="">Seleccione...</option>
                                    <option value="perro" {{ old('especie') == 'perro' ? 'selected' : '' }}>Perro</option>
                                    <option value="gato" {{ old('especie') == 'gato' ? 'selected' : '' }}>Gato</option>
                                    <option value="ave" {{ old('especie') == 'ave' ? 'selected' : '' }}>Ave</option>
                                    <option value="conejo" {{ old('especie') == 'conejo' ? 'selected' : '' }}>Conejo</option>
                                    <option value="reptil" {{ old('especie') == 'reptil' ? 'selected' : '' }}>Reptil</option>
                                    <option value="otro" {{ old('especie') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('especie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Raza -->
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-bookmark"></i> Raza</label>
                                <input type="text" 
                                       name="raza" 
                                       class="form-control @error('raza') is-invalid @enderror" 
                                       value="{{ old('raza') }}"
                                       placeholder="Ej: Labrador, Persa, Canario...">
                                @error('raza')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sexo -->
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Sexo</label>
                                <select name="sexo" class="form-select @error('sexo') is-invalid @enderror">
                                    <option value="">Seleccione...</option>
                                    <option value="macho" {{ old('sexo') == 'macho' ? 'selected' : '' }}>Macho</option>
                                    <option value="hembra" {{ old('sexo') == 'hembra' ? 'selected' : '' }}>Hembra</option>
                                </select>
                                @error('sexo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Color -->
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-palette"></i> Color</label>
                                <input type="text" 
                                       name="color" 
                                       class="form-control @error('color') is-invalid @enderror" 
                                       value="{{ old('color') }}"
                                       placeholder="Ej: Blanco, Negro, Café...">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-calendar3"></i> Fecha de Nacimiento</label>
                                <input type="date" 
                                       name="fecha_nacimiento" 
                                       class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                       value="{{ old('fecha_nacimiento') }}"
                                       max="{{ date('Y-m-d') }}">
                                @error('fecha_nacimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado (solo visible para admin/recepcion) -->
                            @if(!auth()->user()->hasRole('cliente'))
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-toggle-on"></i> Estado</label>
                                <select name="activo" class="form-select">
                                    <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="activo" value="1">
                            @endif

                            <!-- Notas -->
                            <div class="col-12">
                                <label class="form-label"><i class="bi bi-file-text"></i> Notas / Observaciones</label>
                                <textarea name="notas" 
                                          class="form-control @error('notas') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="Alergias, comportamiento, tratamientos previos...">{{ old('notas') }}</textarea>
                                @error('notas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Registrar Mascota
                                </button>
                                <a href="{{ auth()->user()->hasRole('cliente') ? route('cliente.mascotas') : route('mascotas.index') }}" class="btn btn-secondary">
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
    // Preview de imagen
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Validación de tamaño de archivo
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // en MB
            if (fileSize > 2) {
                alert('La imagen no debe superar 2MB');
                e.target.value = '';
                document.getElementById('preview').src = '{{ asset('images/default-pet.png') }}';
            }
        }
    });
</script>
@endpushs