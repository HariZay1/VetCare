@extends('layouts.app')

@section('title', 'Registrar Veterinario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('veterinarios.index') }}">Veterinarios</a></li>
                    <li class="breadcrumb-item active">Registrar Nuevo</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0"><i class="bi bi-person-plus"></i> Registrar Nuevo Veterinario</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="bi bi-person-badge"></i> Datos del Veterinario
                </div>
                <div class="card-body">
                    <form action="{{ route('veterinarios.store') }}" method="POST">
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
                                       placeholder="Ej: Carlos"
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
                                       placeholder="Ej: Rodríguez"
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
                                       value="{{ old('especialidad') }}"
                                       placeholder="Ej: Cirugía Veterinaria, Medicina Interna...">
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
                                       value="{{ old('telefono') }}"
                                       placeholder="Ej: 72345678"
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
                                       value="{{ old('horario') }}"
                                       placeholder="Ej: Lunes a Viernes 8:00-18:00">
                                @error('horario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-toggle-on"></i> Estado
                                </label>
                                <select name="activo" class="form-select">
                                    <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Usuario Existente -->
                        @if($usuariosDisponibles->count() > 0)
                            <h5 class="mb-3"><i class="bi bi-link"></i> Vincular a Usuario Existente</h5>
                            <div class="mb-3">
                                <label class="form-label">Usuario del Sistema</label>
                                <select name="user_id" 
                                        id="user_id" 
                                        class="form-select @error('user_id') is-invalid @enderror"
                                        onchange="toggleCreateUser()">
                                    <option value="">No vincular (crear nuevo usuario abajo)</option>
                                    @foreach($usuariosDisponibles as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->name }} - {{ $usuario->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Usuarios con rol "Veterinario" sin perfil asignado
                                </small>
                            </div>

                            <div class="text-center mb-3">
                                <strong>- O -</strong>
                            </div>
                        @endif

                        <!-- Crear Nuevo Usuario -->
                        <h5 class="mb-3"><i class="bi bi-shield-check"></i> Crear Nuevo Usuario del Sistema</h5>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="crear_usuario" 
                                   name="crear_usuario" 
                                   value="1"
                                   {{ old('crear_usuario') ? 'checked' : '' }}
                                   onchange="togglePasswordFields()">
                            <label class="form-check-label" for="crear_usuario">
                                <strong>Crear nueva cuenta de acceso</strong>
                                <br>
                                <small class="text-muted">
                                    El veterinario podrá iniciar sesión y gestionar su agenda
                                </small>
                            </label>
                        </div>

                        <div id="passwordFields" style="display: {{ old('crear_usuario') ? 'block' : 'none' }};">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-envelope"></i> Correo Electrónico *
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}"
                                           placeholder="correo@ejemplo.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

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
                                    <i class="bi bi-save"></i> Registrar Veterinario
                                </button>
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

@push('scripts')
<script>
    function toggleCreateUser() {
        const userId = document.getElementById('user_id')?.value;
        const checkbox = document.getElementById('crear_usuario');
        
        if (userId) {
            checkbox.checked = false;
            checkbox.disabled = true;
            togglePasswordFields();
        } else {
            checkbox.disabled = false;
        }
    }

    function togglePasswordFields() {
        const checkbox = document.getElementById('crear_usuario');
        const passwordFields = document.getElementById('passwordFields');
        const inputs = passwordFields.querySelectorAll('input');
        
        if (checkbox.checked) {
            passwordFields.style.display = 'block';
            inputs.forEach(input => input.required = true);
        } else {
            passwordFields.style.display = 'none';
            inputs.forEach(input => {
                input.required = false;
                input.value = '';
            });
        }
    }

    // Ejecutar al cargar si hay usuario seleccionado
    document.addEventListener('DOMContentLoaded', toggleCreateUser);
</script>
@endpush