<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CategoriaControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_returns_available_categories(): void
    {
        Categoria::factory()->create([
            'nombre' => 'Alumbrado público',
            'slug' => 'alumbrado-publico',
        ]);

        $response = $this->getJson('/api/categorias');

        $response
            ->assertOk()
            ->assertJsonPath('data.0.nombre', 'Alumbrado público')
            ->assertJsonPath('data.0.slug', 'alumbrado-publico');
    }
}
