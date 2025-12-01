@extends('layouts.app')

@section('title', auth()->user()->hasRole('cliente') ? 'Mis Mascotas' : 'Gestión de Mascotas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="bi bi-heart-pulse"></i> 
                {{ auth()->user()->hasRole('cliente') ? 'Mis Mascotas' : 'Gestión de Mascotas' }}
            </h1>
            <p class="text-muted">
                {{ auth()->user()->hasRole('cliente') ? 'Tus mascotas registradas en la veterinaria' : 'Administra el registro de mascotas' }}
            </p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group" aria-label="Acciones">
                @if(auth()->user()->hasRole('cliente'))
                    <a href="{{ route('cliente.mascotas.export.excel') }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Excel
                    </a>
                    <a href="{{ route('cliente.mascotas.export.pdf') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                @else
                    <a href="{{ route('mascotas.export.excel') }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Excel
                    </a>
                    <a href="{{ route('mascotas.export.pdf') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                @endif
                <a href="{{ route('mascotas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Registrar Mascota
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda SOLO para admin/recepcion -->
    @if(!auth()->user()->hasRole('cliente'))
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('mascotas.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><i class="bi bi-search"></i> Buscar</label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Nombre, especie, propietario..."
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-filter"></i> Especie</label>
                        <select name="especie" class="form-select">
                            <option value="">Todas</option>
                            <option value="perro" {{ request('especie') == 'perro' ? 'selected' : '' }}>Perro</option>
                            <option value="gato" {{ request('especie') == 'gato' ? 'selected' : '' }}>Gato</option>
                            <option value="ave" {{ request('especie') == 'ave' ? 'selected' : '' }}>Ave</option>
                            <option value="conejo" {{ request('especie') == 'conejo' ? 'selected' : '' }}>Conejo</option>
                            <option value="reptil" {{ request('especie') == 'reptil' ? 'selected' : '' }}>Reptil</option>
                            <option value="otro" {{ request('especie') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-person"></i> Propietario</label>
                        <select name="propietario_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($propietarios as $prop)
                                <option value="{{ $prop->id }}" {{ request('propietario_id') == $prop->id ? 'selected' : '' }}>
                                    {{ $prop->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-toggle-on"></i> Estado</label>
                        <select name="activo" class="form-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </a>
                        <span class="text-muted ms-3">
                            <i class="bi bi-info-circle"></i> {{ $mascotas->total() }} mascota(s) encontrada(s)
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Grid de Mascotas -->
    <div class="row g-4">
        @forelse($mascotas as $mascota)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm {{ !$mascota->activo ? 'border-danger' : '' }}">
                    <!-- Imagen -->
                    <div class="position-relative">
                        <img src="{{ $mascota->foto_url }}" 
                             class="card-img-top" 
                             alt="{{ $mascota->nombre }}"
                             style="height: 220px; object-fit: cover;">
                        
                        <!-- Badge de Estado -->
                        @if(!$mascota->activo)
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                                Inactivo
                            </span>
                        @endif
                        
                        <!-- Badge de Especie -->
                        <span class="position-absolute bottom-0 start-0 m-2 badge bg-primary">
                            {{ ucfirst($mascota->especie) }}
                        </span>
                    </div>

                    <!-- Información -->
                    <div class="card-body">
                        <h5 class="card-title mb-2">
                            <i class="bi bi-heart-fill text-danger"></i> {{ $mascota->nombre }}
                        </h5>
                        
                        <p class="card-text mb-2">
                            @if(!auth()->user()->hasRole('cliente'))
                            <small class="text-muted d-block">
                                <i class="bi bi-person"></i> {{ $mascota->propietario->nombre_completo }}
                            </small>
                            @endif
                            
                            @if($mascota->raza)
                                <small class="text-muted d-block">
                                    <i class="bi bi-tag"></i> {{ $mascota->raza }}
                                </small>
                            @endif
                            
                            @if($mascota->edad)
                                <small class="text-muted d-block">
                                    <i class="bi bi-calendar3"></i> {{ $mascota->edad }}
                                </small>
                            @endif
                            
                            @if($mascota->sexo)
                                <small class="text-muted d-block">
                                    <i class="bi bi-gender-{{ $mascota->sexo === 'macho' ? 'male' : 'female' }}"></i> 
                                    {{ ucfirst($mascota->sexo) }}
                                </small>
                            @endif
                        </p>

                        @if($mascota->color)
                            <span class="badge bg-secondary mb-2">
                                <i class="bi bi-palette"></i> {{ $mascota->color }}
                            </span>
                        @endif
                    </div>

                    <!-- Acciones -->
                    <div class="card-footer bg-white border-0">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('mascotas.show', $mascota) }}" 
                               class="btn btn-sm btn-info"
                               title="Ver Detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('mascotas.edit', $mascota) }}" 
                               class="btn btn-sm btn-primary"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(!auth()->user()->hasRole('cliente'))
                            <button type="button" 
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $mascota->id }}"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Eliminar SOLO para admin/recepcion -->
            @if(!auth()->user()->hasRole('cliente'))
            <div class="modal fade" id="deleteModal{{ $mascota->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de eliminar a <strong>{{ $mascota->nombre }}</strong>?</p>
                            <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-3">No se encontraron mascotas</p>
                        <a href="{{ route('mascotas.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Registrar Primera Mascota
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if(method_exists($mascotas, 'hasPages') && $mascotas->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $mascotas->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection