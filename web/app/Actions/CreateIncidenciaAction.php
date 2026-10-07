<?php

namespace App\Actions;

use App\EstadoIncidencia;
use App\Models\Incidencia;
use Illuminate\Http\UploadedFile;

class CreateIncidenciaAction
{
    /**
     * @param  array{categoria_id: int, descripcion: string, latitud: float|int|string, longitud: float|int|string}  $attributes
     */
    public function handle(array $attributes, ?UploadedFile $foto = null): Incidencia
    {
        if ($foto !== null) {
            $attributes['foto_path'] = $foto->store('incidencias', 'public');
        }

        $attributes['estado'] = EstadoIncidencia::Reportada;

        return Incidencia::query()->create($attributes);
    }
}
