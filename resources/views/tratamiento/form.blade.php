<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="cita_id" class="form-label">{{ __('Cita Id') }}</label>
            <input type="text" name="cita_id" class="form-control @error('cita_id') is-invalid @enderror" value="{{ old('cita_id', $tratamiento?->cita_id) }}" id="cita_id" placeholder="Cita Id">
            {!! $errors->first('cita_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="mascota_id" class="form-label">{{ __('Mascota Id') }}</label>
            <input type="text" name="mascota_id" class="form-control @error('mascota_id') is-invalid @enderror" value="{{ old('mascota_id', $tratamiento?->mascota_id) }}" id="mascota_id" placeholder="Mascota Id">
            {!! $errors->first('mascota_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $tratamiento?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="receta" class="form-label">{{ __('Receta') }}</label>
            <input type="text" name="receta" class="form-control @error('receta') is-invalid @enderror" value="{{ old('receta', $tratamiento?->receta) }}" id="receta" placeholder="Receta">
            {!! $errors->first('receta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="costo" class="form-label">{{ __('Costo') }}</label>
            <input type="text" name="costo" class="form-control @error('costo') is-invalid @enderror" value="{{ old('costo', $tratamiento?->costo) }}" id="costo" placeholder="Costo">
            {!! $errors->first('costo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_seguimiento" class="form-label">{{ __('Fecha Seguimiento') }}</label>
            <input type="text" name="fecha_seguimiento" class="form-control @error('fecha_seguimiento') is-invalid @enderror" value="{{ old('fecha_seguimiento', $tratamiento?->fecha_seguimiento) }}" id="fecha_seguimiento" placeholder="Fecha Seguimiento">
            {!! $errors->first('fecha_seguimiento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>