<?php

namespace App\Models;

use App\EstadoIncidencia;
use Database\Factories\IncidenciaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incidencia extends Model
{
    /** @use HasFactory<IncidenciaFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'categoria_id',
        'descripcion',
        'latitud',
        'longitud',
        'estado',
        'foto_path',
    ];

    protected $attributes = [
        'estado' => EstadoIncidencia::Reportada->value,
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'latitud' => 'float',
            'longitud' => 'float',
            'estado' => EstadoIncidencia::class,
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }
}
