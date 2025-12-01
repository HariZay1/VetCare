@extends('layouts.app')

@section('title', 'Gestión de Veterinarios')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="bi bi-hospital"></i> Gestión de Veterinarios</h1>
            <p class="text-muted">Administra el equipo médico veterinario</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('veterinarios.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Registrar Veterinario
            </a>
        </div>
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('veterinarios.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label"><i class="bi bi-search"></i> Buscar</label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Nombre, especialidad, teléfono..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-toggle-on"></i> Estado</label>
                        <select name="activo" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </div>

                @if(request()->hasAny(['search', 'activo']))
                    <div class="mt-3">
                        <a href="{{ route('veterinarios.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </a>
                        <span class="text-muted ms-2">
                            <i class="bi bi-info-circle"></i> {{ $veterinarios->total() }} veterinario(s) encontrado(s)
                        </span>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Grid de Veterinarios -->
    <div class="row g-4">
        @forelse($veterinarios as $veterinario)
            <div class="col-xl-4 col-lg-6">
                <div class="card h-100 shadow-sm {{ !$veterinario->activo ? 'border-danger' : 'border-primary' }}">
                    <div class="card-header {{ $veterinario->activo ? 'bg-primary text-white' : 'bg-danger text-white' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-person-badge"></i> 
                                Dr. {{ $veterinario->nombre_completo }}
                            </span>
                            @if($veterinario->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-dark">Inactivo</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Especialidad -->
                        @if($veterinario->especialidad)
                            <p class="mb-2">
                                <i class="bi bi-award text-primary"></i> 
                                <strong>{{ $veterinario->especialidad }}</strong>
                            </p>
                        @endif

                        <!-- Contacto -->
                        <p class="mb-2">
                            <i class="bi bi-telephone text-success"></i> {{ $veterinario->telefono }}
                        </p>

                        @if($veterinario->user)
                            <p class="mb-2">
                                <i class="bi bi-envelope text-info"></i> {{ $veterinario->user->email }}
                            </p>
                        @endif

                        <!-- Horario -->
                        @if($veterinario->horario)
                            <p class="mb-2">
                                <i class="bi bi-clock text-warning"></i> {{ $veterinario->horario }}
                            </p>
                        @endif

                        <!-- Estadísticas -->
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="mb-0 text-primary">{{ $veterinario->citas->count() }}</h4>
                                <small class="text-muted">Citas Totales</small>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-0 text-success">
                                    {{ $veterinario->citas->where('estado', 'completada')->count() }}
                                </h4>
                                <small class="text-muted">Completadas</small>
                            </div>
                        </div>

                        <!-- Usuario -->
                        @if($veterinario->user)
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="bi bi-check-circle-fill"></i> Usuario del sistema activo
                            </div>
                        @else
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="bi bi-exclamation-triangle"></i> Sin acceso al sistema
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('veterinarios.show', $veterinario) }}" 
                               class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            <a href="{{ route('veterinarios.edit', $veterinario) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $veterinario->id }}">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Eliminar -->
            <div class="modal fade" id="deleteModal{{ $veterinario->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de eliminar a <strong>Dr. {{ $veterinario->nombre_completo }}</strong>?</p>
                            @if($veterinario->citas->count() > 0)
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    Este veterinario tiene <strong>{{ $veterinario->citas->count() }} cita(s)</strong> asociada(s). 
                                    No se puede eliminar.
                                </div>
                            @else
                                <p class="text-muted">Esta acción no se puede deshacer.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            @if($veterinario->citas->count() == 0)
                                <form action="{{ route('veterinarios.destroy', $veterinario) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-hospital fs-1 text-muted"></i>
                        <p class="text-muted mt-3">No hay veterinarios registrados</p>
                        <a href="{{ route('veterinarios.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Registrar Primer Veterinario
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($veterinarios->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $veterinarios->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection