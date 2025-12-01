@extends('layouts.app')

@section('title', 'Editar Propietario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('propietarios.index') }}">Propietarios</a></li>
                    <li class="breadcrumb-item active">Editar: {{ $propietario->nombre_completo }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0"><i class="bi bi-pencil"></i> Editar Propietario</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-person-badge"></i> Datos del Propietario
                </div>
                <div class="card-body">
                    <form action="{{ route('propietarios.update', $propietario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" 
                                       name="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre', $propietario->nombre) }}"
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
                                       value="{{ old('apellido', $propietario->apellido) }}"
                                       required>
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-card-text"></i> Carnet de Identidad *
                                </label>
                                <input type="text" 
                                       name="ci" 
                                       class="form-control @error('ci') is-invalid @enderror" 
                                       value="{{ old('ci', $propietario->ci) }}"
                                       required>
                                @error('ci')
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
                                       value="{{ old('telefono', $propietario->telefono) }}"
                                       required>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i> Correo Electrónico *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $propietario->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-geo-alt"></i> Dirección
                                </label>
                                <textarea name="direccion" 
                                          class="form-control @error('direccion') is-invalid @enderror" 
                                          rows="2">{{ old('direccion', $propietario->direccion) }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($propietario->user)
                            <div class="alert alert-success mt-3">
                                <i class="bi bi-check-circle-fill"></i> 
                                Este propietario tiene acceso al sistema como <strong>{{ $propietario->user->email }}</strong>
                            </div>
                        @endif

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('propietarios.show', $propietario) }}" class="btn btn-info">
                                    <i class="bi bi-eye"></i> Ver Perfil
                                </a>
                                <a href="{{ route('propietarios.index') }}" class="btn btn-secondary">
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