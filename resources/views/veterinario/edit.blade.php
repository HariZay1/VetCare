@extends('layouts.app')

@section('title', 'Editar Veterinario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('veterinarios.index') }}">Veterinarios</a></li>
                    <li class="breadcrumb-item active">Editar: {{ $veterinario->nombre_completo }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0"><i class="bi bi-pencil"></i> Editar Veterinario</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-person-badge"></i> Datos del Veterinario
                </div>
                <div class="card-body">
                    <form action="{{ route('veterinarios.update', $veterinario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" 
                                       name="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre', $veterinario->nombre) }}"
                                       required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Apellido *</label>
                                <input type="text" 
                                       name="apellido" 
                                       class="form-control @error('apellido') is-invalid @enderror" 
                                       value="{{ old('apellido', $veterinario->apellido) }}"
                                       required>
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-award"></i> Especialidad
                                </label>
                                <input type="text" 
                                       name="especialidad" 
                                       class="form-control @error('especialidad') is-invalid @enderror" 
                                       value="{{ old('especialidad', $veterinario->especialidad) }}">
                                @error('especialidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-telephone"></i> Teléfono *
                                </label>
                                <input type="text" 
                                       name="telefono" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       value="{{ old('telefono', $veterinario->telefono) }}"
                                       required>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-clock"></i> Horario de Atención
                                </label>
                                <input type="text" 
                                       name="horario" 
                                       class="form-control @error('horario') is-invalid @enderror" 
                                       value="{{ old('horario', $veterinario->horario) }}">
                                @error('horario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-toggle-on"></i> Estado
                                </label>
                                <select name="activo" class="form-select">
                                    <option value="1" {{ old('activo', $veterinario->activo) == 1 ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('activo', $veterinario->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        @if($veterinario->user)
                            <div class="alert alert-success mt-3">
                                <i class="bi bi-check-circle-fill"></i> 
                                Este veterinario tiene acceso al sistema como <strong>{{ $veterinario->user->email }}</strong>
                            </div>
                        @endif

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('veterinarios.show', $veterinario) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> Ver Perfil
                                </a>
                                <a href="{{ route('veterinarios.index') }}" class="btn btn-secondary">
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