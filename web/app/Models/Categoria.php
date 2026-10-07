<?php

namespace App\Models;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /** @use HasFactory<CategoriaFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'nombre',
        'slug',
        'icono',
        'color',
    ];

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }
}
