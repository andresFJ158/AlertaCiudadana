<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class IncidenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'categoria_id' => $this->categoria_id,
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'descripcion' => $this->descripcion,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'estado' => $this->estado->value,
            'foto_url' => $this->foto_path === null
                ? null
                : Storage::disk('public')->url($this->foto_path),
            'creada_en' => $this->created_at?->toIso8601String(),
        ];
    }
}
