@extends('adminlte::page')

@section('title', 'Registrar alerta')

@section('content_header')
    <x-adminlte-content-header title="Registrar una alerta" :breadcrumbs="[
        ['label' => 'Alertas', 'url' => route('alertas.index')],
        ['label' => 'Registrar'],
    ]" />
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="card card-danger shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0"><i class="bi bi-megaphone-fill me-2"></i>Datos de la incidencia</h3>
                </div>

                <form action="{{ route('alertas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <p class="text-body-secondary">
                            Describe el problema y señala su ubicación. Los campos con * son obligatorios.
                        </p>

                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select id="categoria_id" name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                                <option value="">Selecciona una categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción *</label>
                            <textarea id="descripcion" name="descripcion" rows="4" maxlength="1000"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                placeholder="Ej.: Luminaria apagada frente a la plaza" required>{{ old('descripcion') }}</textarea>
                            <div class="form-text">Máximo 1.000 caracteres.</div>
                            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="latitud" class="form-label">Latitud *</label>
                                <input id="latitud" name="latitud" type="number" step="any" min="-90" max="90"
                                    value="{{ old('latitud') }}"
                                    class="form-control @error('latitud') is-invalid @enderror" required>
                                @error('latitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="longitud" class="form-label">Longitud *</label>
                                <input id="longitud" name="longitud" type="number" step="any" min="-180" max="180"
                                    value="{{ old('longitud') }}"
                                    class="form-control @error('longitud') is-invalid @enderror" required>
                                @error('longitud')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3 mb-4">
                            <button id="obtenerUbicacion" type="button" class="btn btn-outline-primary">
                                <i class="bi bi-crosshair me-1"></i>Usar mi ubicación
                            </button>
                            <span id="estadoUbicacion" class="small text-body-secondary" aria-live="polite">
                                También puedes escribir las coordenadas manualmente.
                            </span>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Fotografía opcional</label>
                            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png"
                                class="form-control @error('foto') is-invalid @enderror">
                            <div class="form-text">JPEG o PNG, máximo 5 MB.</div>
                            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('alertas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-send-fill me-1"></i>Enviar alerta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('obtenerUbicacion').addEventListener('click', function () {
            const estado = document.getElementById('estadoUbicacion');

            if (!navigator.geolocation) {
                estado.textContent = 'Tu navegador no permite obtener la ubicación.';
                return;
            }

            estado.textContent = 'Obteniendo ubicación…';
            navigator.geolocation.getCurrentPosition(function (posicion) {
                document.getElementById('latitud').value = posicion.coords.latitude.toFixed(7);
                document.getElementById('longitud').value = posicion.coords.longitude.toFixed(7);
                estado.textContent = 'Ubicación cargada correctamente.';
            }, function () {
                estado.textContent = 'No se pudo obtener la ubicación. Ingresa las coordenadas manualmente.';
            });
        });
    </script>
@stop

@section('footer')
    <strong>Alerta Ciudadana</strong> · Iteración 0 · Andrés
@stop
