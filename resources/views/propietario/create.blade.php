@extends('layouts.app')

@section('title', 'Registrar Propietario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('propietarios.index') }}">Propietarios</a></li>
                    <li class="breadcrumb-item active">Registrar Nuevo</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0"><i class="bi bi-person-plus"></i> Registrar Nuevo Propietario</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-person-badge"></i> Datos del Propietario
                </div>
                <div class="card-body">
                    <form action="{{ route('propietarios.store') }}" method="POST">
                        @csrf

                        <!-- Información Personal -->
                        <h5 class="mb-3"><i class="bi bi-person-circle"></i> Información Personal</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" 
                                       name="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       value="{{ old('nombre') }}"
                                       placeholder="Ej: Juan"
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
                                       value="{{ old('apellido') }}"
                                       placeholder="Ej: Pérez"
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
                                       value="{{ old('ci') }}"
                                       placeholder="Ej: 12345678"
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
                                       value="{{ old('telefono') }}"
                                       placeholder="Ej: 70123456"
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
                                       value="{{ old('email') }}"
                                       placeholder="correo@ejemplo.com"
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
                                          rows="2"
                                          placeholder="Calle, número, zona...">{{ old('direccion') }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Crear Usuario -->
                        <h5 class="mb-3"><i class="bi bi-shield-check"></i> Acceso al Sistema (Opcional)</h5>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="crear_usuario" 
                                   name="crear_usuario" 
                                   value="1"
                                   {{ old('crear_usuario') ? 'checked' : '' }}
                                   onchange="togglePasswordFields()">
                            <label class="form-check-label" for="crear_usuario">
                                <strong>Crear cuenta de usuario</strong>
                                <br>
                                <small class="text-muted">
                                    El propietario podrá iniciar sesión y gestionar sus mascotas y citas
                                </small>
                            </label>
                        </div>

                        <div id="passwordFields" style="display: {{ old('crear_usuario') ? 'block' : 'none' }};">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-lock"></i> Contraseña *
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Mínimo 8 caracteres">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-lock-fill"></i> Confirmar Contraseña *
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Repite la contraseña">
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Registrar Propietario
                                </button>
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

@push('scripts')
<script>
    function togglePasswordFields() {
        const checkbox = document.getElementById('crear_usuario');
        const passwordFields = document.getElementById('passwordFields');
        const passwordInputs = passwordFields.querySelectorAll('input');
        
        if (checkbox.checked) {
            passwordFields.style.display = 'block';
            passwordInputs.forEach(input => input.required = true);
        } else {
            passwordFields.style.display = 'none';
            passwordInputs.forEach(input => {
                input.required = false;
                input.value = '';
            });
        }
    }
</script>
@endpush