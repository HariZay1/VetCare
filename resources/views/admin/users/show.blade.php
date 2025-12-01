@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="bi bi-person-badge"></i> Perfil de Usuario</h1>
            <p class="text-muted">Vista rápida del usuario y sus perfiles asociados</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $user->name }}</h5>
            <p class="text-muted">{{ $user->email }}</p>
            <p><strong>Rol:</strong> {{ $user->getRoleNames()->first() ?? '-' }}</p>
            <div class="mt-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Editar usuario</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>

    @if($propietario)
    <div class="card mb-3">
        <div class="card-header">Perfil de Propietario</div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $propietario->nombre_completo }}</p>
            <p><strong>CI:</strong> {{ $propietario->ci ?? '-' }}</p>
            <p><strong>Teléfono:</strong> {{ $propietario->telefono ?? '-' }}</p>
            <a href="{{ route('propietarios.edit', $propietario) }}" class="btn btn-sm btn-outline-primary">Editar Perfil de Propietario</a>
        </div>
    </div>
    @endif

    @if($veterinario)
    <div class="card mb-3">
        <div class="card-header">Perfil de Veterinario</div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $veterinario->nombre_completo }}</p>
            <p><strong>Especialidad:</strong> {{ $veterinario->especialidad ?? '-' }}</p>
            <p><strong>Teléfono:</strong> {{ $veterinario->telefono ?? '-' }}</p>
            <a href="{{ route('veterinarios.edit', $veterinario) }}" class="btn btn-sm btn-outline-primary">Editar Perfil de Veterinario</a>
        </div>
    </div>
    @endif
</div>
@endsection
