@extends('layouts.app')

@section('title', 'Editar perfil')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="bi bi-person-lines-fill"></i> Editar mi perfil de Propietario</h1>
            <p class="text-muted">Actualiza tus datos de contacto y documento.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('propietarios.update.mi') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $propietario->nombre) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido *</label>
                                <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $propietario->apellido) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">CI *</label>
                                <input type="text" name="ci" class="form-control" value="{{ old('ci', $propietario->ci) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Teléfono *</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $propietario->telefono) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Correo electrónico *</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $propietario->email) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Dirección</label>
                                <textarea name="direccion" class="form-control">{{ old('direccion', $propietario->direccion) }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
