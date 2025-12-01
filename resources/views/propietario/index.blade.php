@extends('layouts.app')

@section('title', 'Gestión de Propietarios')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="bi bi-people"></i> Gestión de Propietarios</h1>
            <p class="text-muted">Administra los clientes de la veterinaria</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('propietarios.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Registrar Propietario
            </a>
            <div class="btn-group ms-2" role="group">
                <a href="{{ route('propietarios.export.excel') }}" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>
                <a href="{{ route('propietarios.export.pdf') }}" class="btn btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('propietarios.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-10">
                        <label class="form-label"><i class="bi bi-search"></i> Buscar</label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Nombre, CI, teléfono, email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </div>
                @if(request('search'))
                    <div class="mt-2">
                        <a href="{{ route('propietarios.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar búsqueda
                        </a>
                        <span class="text-muted ms-2">
                            <i class="bi bi-info-circle"></i> {{ $propietarios->total() }} propietario(s) encontrado(s)
                        </span>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabla de Propietarios -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Propietario</th>
                            <th>CI</th>
                            <th>Contacto</th>
                            <th>Mascotas</th>
                            <th>Citas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propietarios as $propietario)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-person fs-5 text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $propietario->nombre_completo }}</strong>
                                            @if($propietario->user)
                                                <br><small class="text-success">
                                                    <i class="bi bi-check-circle-fill"></i> Usuario activo
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="bi bi-card-text text-muted"></i> {{ $propietario->ci }}
                                </td>
                                <td>
                                    <div>
                                        <i class="bi bi-telephone"></i> {{ $propietario->telefono }}
                                    </div>
                                    <div>
                                        <i class="bi bi-envelope"></i> 
                                        <small>{{ $propietario->email }}</small>
                                    </div>
                                    @if($propietario->direccion)
                                        <div>
                                            <i class="bi bi-geo-alt"></i> 
                                            <small class="text-muted">{{ Str::limit($propietario->direccion, 30) }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info fs-6">
                                        <i class="bi bi-heart"></i> {{ $propietario->mascotas->count() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-calendar-check"></i> {{ $propietario->citas->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('propietarios.show', $propietario) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('propietarios.edit', $propietario) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $propietario->id }}"
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Eliminar -->
                            <div class="modal fade" id="deleteModal{{ $propietario->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de eliminar a <strong>{{ $propietario->nombre_completo }}</strong>?</p>
                                            @if($propietario->mascotas->count() > 0)
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle"></i> 
                                                    Este propietario tiene <strong>{{ $propietario->mascotas->count() }} mascota(s)</strong> registrada(s). 
                                                    No se puede eliminar.
                                                </div>
                                            @else
                                                <p class="text-muted">Esta acción no se puede deshacer.</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            @if($propietario->mascotas->count() == 0)
                                                <form action="{{ route('propietarios.destroy', $propietario) }}" method="POST" class="d-inline">
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
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-3">No hay propietarios registrados</p>
                                    <a href="{{ route('propietarios.create') }}" class="btn btn-primary">
                                        <i class="bi bi-person-plus"></i> Registrar Primer Propietario
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($propietarios->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $propietarios->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection