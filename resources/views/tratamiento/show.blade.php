@extends('layouts.app')

@section('template_title')
    {{ $tratamiento->name ?? __('Show') . " " . __('Tratamiento') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Tratamiento</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('tratamientos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cita Id:</strong>
                                    {{ $tratamiento->cita_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Mascota Id:</strong>
                                    {{ $tratamiento->mascota_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $tratamiento->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Receta:</strong>
                                    {{ $tratamiento->receta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Costo:</strong>
                                    {{ $tratamiento->costo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Seguimiento:</strong>
                                    {{ $tratamiento->fecha_seguimiento }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
