<?php

namespace Database\Factories;

use App\EstadoIncidencia;
use App\Models\Categoria;
use App\Models\Incidencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Incidencia>
 */
class IncidenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categoria_id' => Categoria::factory(),
            'descripcion' => fake()->sentence(),
            'latitud' => fake()->latitude(-22.9, -9.6),
            'longitud' => fake()->longitude(-69.7, -57.4),
            'estado' => EstadoIncidencia::Reportada,
            'foto_path' => null,
        ];
    }
}
