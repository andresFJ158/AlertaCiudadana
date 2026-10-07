<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Categoria;
use App\Models\Incidencia;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class IncidenciaWebControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_create_page_shows_available_categories(): void
    {
        Categoria::factory()->create(['nombre' => 'Residuos']);

        $response = $this->get('/alertas/crear');

        $response
            ->assertOk()
            ->assertSeeText('Registrar una alerta')
            ->assertSeeText('Residuos');
    }

    public function test_valid_form_creates_incident_and_shows_identifier(): void
    {
        $categoria = Categoria::factory()->create();

        $response = $this->post('/alertas', [
            'categoria_id' => $categoria->id,
            'descripcion' => 'Semáforo sin funcionar en la esquina',
            'latitud' => -17.7833,
            'longitud' => -63.1821,
        ]);

        $incidencia = Incidencia::query()->sole();
        $response
            ->assertRedirectToRoute('alertas.index')
            ->assertSessionHas('success', "Alerta #{$incidencia->id} registrada correctamente.");

        $this->assertDatabaseHas('incidencias', [
            'id' => $incidencia->id,
            'estado' => 'reportada',
        ]);
    }

    public function test_invalid_form_returns_errors_and_does_not_create_incident(): void
    {
        $response = $this->from('/alertas/crear')->post('/alertas');

        $response
            ->assertRedirect('/alertas/crear')
            ->assertSessionHasErrors([
                'categoria_id',
                'descripcion',
                'latitud',
                'longitud',
            ]);

        $this->assertDatabaseCount('incidencias', 0);
    }

    public function test_list_escapes_user_provided_description(): void
    {
        Incidencia::factory()->create([
            'descripcion' => '<script>alert("xss")</script>',
        ]);

        $response = $this->get('/alertas');

        $response
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert("xss")</script>', false);
    }
}
