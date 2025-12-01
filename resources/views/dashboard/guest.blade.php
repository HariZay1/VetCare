@extends('layouts.app')

@section('title', 'Bienvenido')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="bi bi-shield-exclamation"></i> Acceso limitado</h1>
            <p class="text-muted">Tu cuenta aún no tiene roles asignados.</p>
        </div>
    </div>

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <p>
                Si creaste esta cuenta recientemente, contacta con el administrador para que te asigne el rol correspondiente.
            </p>
            <p class="mb-0">
                - Si eres cliente, solicita que te asignen el rol <strong>cliente</strong> para registrar mascotas y solicitar citas.<br>
                - Si trabajas en la recepción o eres administrador, solicita el rol correspondiente (<strong>recepcion</strong> o <strong>admin</strong>).
            </p>

            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Refrescar</a>
                <a href="{{ url('/') }}" class="btn btn-outline-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection
