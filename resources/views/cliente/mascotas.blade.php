@extends('layouts.app')

@section('title', 'Mis Mascotas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="bi bi-heart-pulse"></i> Mis Mascotas</h1>
            <p class="text-muted">Tus mascotas registradas en la veterinaria</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('mascotas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Registrar Mascota
            </a>
        </div>
    </div>

    <div class="row g-4">
        @forelse($mascotas as $mascota)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm {{ !$mascota->activo ? 'border-danger' : '' }}">
                    <div class="position-relative">
                        <img src="{{ $mascota->foto_url }}" 
                             class="card-img-top" 
                             alt="{{ $mascota->nombre }}"
                             style="height: 220px; object-fit: cover;">

                        @if(!$mascota->activo)
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Inactivo</span>
                        @endif

                        <span class="position-absolute bottom-0 start-0 m-2 badge bg-primary">{{ ucfirst($mascota->especie) }}</span>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title mb-2"><i class="bi bi-heart-fill text-danger"></i> {{ $mascota->nombre }}</h5>

                        @if($mascota->raza)
                            <small class="text-muted d-block"><i class="bi bi-tag"></i> {{ $mascota->raza }}</small>
                        @endif

                        @if($mascota->edad)
                            <small class="text-muted d-block"><i class="bi bi-calendar3"></i> {{ $mascota->edad }}</small>
                        @endif

                        @if($mascota->sexo)
                            <small class="text-muted d-block"><i class="bi bi-gender-{{ $mascota->sexo === 'macho' ? 'male' : 'female' }}"></i> {{ ucfirst($mascota->sexo) }}</small>
                        @endif
                    </div>

                    <div class="card-footer bg-white border-0">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('mascotas.show', $mascota) }}" class="btn btn-sm btn-info" title="Ver Detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-sm btn-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
</div>
@endsection
