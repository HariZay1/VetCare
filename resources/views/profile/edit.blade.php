@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="bi bi-person-circle"></i> Editar Perfil</h1>
            <p class="text-muted">Actualiza tu información de usuario</p>
        </div>
    </div>

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success">Perfil actualizado correctamente.</div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary">Guardar cambios</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h5>Eliminar cuenta</h5>
                    <p class="text-muted">Si eliminas tu cuenta se borrarán tus datos. Esta acción es irreversible.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿Estás seguro de eliminar tu cuenta? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label class="form-label">Confirma tu contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-danger">Eliminar cuenta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
