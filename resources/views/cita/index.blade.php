@extends('layouts.app')

@section('title', 'Gestión de Citas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="bi bi-calendar-check"></i> Gestión de Citas</h1>
            <p class="text-muted">Administra las citas veterinarias</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('citas.create') }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> Nueva Cita
            </a>
            <div class="btn-group ms-2">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download"></i> Exportar
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('citas.export.excel') }}">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('citas.export.pdf') }}">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('citas.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-search"></i> Buscar</label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Mascota, propietario, motivo..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-calendar3"></i> Fecha</label>
                        <input type="date" 
                               name="fecha" 
                               class="form-control" 
                               value="{{ request('fecha') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-flag"></i> Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-person-badge"></i> Veterinario</label>
                        <select name="veterinario_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($veterinarios as $vet)
                                <option value="{{ $vet->id }}" {{ request('veterinario_id') == $vet->id ? 'selected' : '' }}>
                                    {{ $vet->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </div>

                @if(request()->hasAny(['search', 'fecha', 'estado', 'veterinario_id']))
                    <div class="mt-3">
                        <a href="{{ route('citas.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar filtros
                        </a>
                        <span class="text-muted ms-2">
                            <i class="bi bi-info-circle"></i> {{ $citas->total() }} cita(s) encontrada(s)
                        </span>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabla de Citas -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Mascota</th>
                            <th>Propietario</th>
                            <th>Veterinario</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($citas as $cita)
                            <tr class="{{ $cita->fecha_hora->isToday() ? 'table-primary' : '' }}">
                                <td>
                                    <strong>{{ $cita->fecha_hora->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $cita->fecha_hora->format('H:i') }}
                                    </small>
                                    @if($cita->fecha_hora->isToday())
                                        <br><span class="badge bg-primary">Hoy</span>
                                    @elseif($cita->fecha_hora->isTomorrow())
                                        <br><span class="badge bg-info">Mañana</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $cita->mascota->foto_url }}" 
                                             class="rounded me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             alt="{{ $cita->mascota->nombre }}">
                                        <div>
                                            <strong>{{ $cita->mascota->nombre }}</strong><br>
                                            <small class="text-muted">{{ ucfirst($cita->mascota->especie) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $cita->propietario->nombre_completo }}<br>
                                    <small class="text-muted">
                                        <i class="bi bi-telephone"></i> {{ $cita->propietario->telefono }}
                                    </small>
                                </td>
                                <td>
                                    @if($cita->veterinario)
                                        <i class="bi bi-person-badge text-primary"></i> 
                                        {{ $cita->veterinario->nombre_completo }}
                                    @else
                                        <span class="badge bg-secondary">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    {{ Str::limit($cita->motivo, 40) }}
                                    @if($cita->notas)
                                        <br><small class="text-muted">
                                            <i class="bi bi-info-circle"></i> {{ Str::limit($cita->notas, 30) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $cita->estado_badge }}">
                                        {{ $cita->estado_texto }}
                                    </span>
                                    @if($cita->costo)
                                        <br><small class="text-success fw-bold">
                                            Bs. {{ number_format($cita->costo, 2) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('citas.show', $cita) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('citas.edit', $cita) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($cita->estado === 'completada')
                                            <a href="{{ route('citas.pdf', $cita) }}" 
                                               class="btn btn-sm btn-danger" 
                                               title="PDF">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                    <p class="text-muted mt-3">No hay citas registradas</p>
                                    <a href="{{ route('citas.create') }}" class="btn btn-primary">
                                        <i class="bi bi-calendar-plus"></i> Crear Primera Cita
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($citas->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $citas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection