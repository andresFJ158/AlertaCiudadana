<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Alumbrado público', 'slug' => 'alumbrado-publico', 'icono' => 'bi bi-lightbulb', 'color' => 'warning'],
            ['nombre' => 'Baches y vías', 'slug' => 'baches-vias', 'icono' => 'bi bi-cone-striped', 'color' => 'danger'],
            ['nombre' => 'Residuos', 'slug' => 'residuos', 'icono' => 'bi bi-trash', 'color' => 'success'],
            ['nombre' => 'Seguridad ciudadana', 'slug' => 'seguridad-ciudadana', 'icono' => 'bi bi-shield-exclamation', 'color' => 'primary'],
            ['nombre' => 'Semáforos', 'slug' => 'semaforos', 'icono' => 'bi bi-stoplights', 'color' => 'info'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::query()->updateOrCreate(
                ['slug' => $categoria['slug']],
                $categoria,
            );
        }
    }
}
