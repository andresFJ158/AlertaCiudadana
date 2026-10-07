<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Categoria;
use App\Models\Incidencia;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IncidenciaControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_valid_payload_creates_incident_and_returns_201(): void
    {
        $categoria = Categoria::factory()->create();

        $response = $this->postJson('/api/incidencias', [
            'categoria_id' => $categoria->id,
            'descripcion' => 'Luminaria apagada frente a la plaza',
            'latitud' => -17.7833,
            'longitud' => -63.1821,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.categoria_id', $categoria->id)
            ->assertJsonPath('data.descripcion', 'Luminaria apagada frente a la plaza')
            ->assertJsonPath('data.estado', 'reportada')
            ->assertJsonPath('data.foto_url', null);

        $this->assertDatabaseHas('incidencias', [
            'categoria_id' => $categoria->id,
            'descripcion' => 'Luminaria apagada frente a la plaza',
            'estado' => 'reportada',
        ]);
    }

    public function test_valid_image_is_stored_with_the_incident(): void
    {
        Storage::fake('public');
        $categoria = Categoria::factory()->create();
        $foto = UploadedFile::fake()->image('poste.png');

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/api/incidencias', [
                'categoria_id' => $categoria->id,
                'descripcion' => 'Poste inclinado junto a una vivienda',
                'latitud' => -17.7833,
                'longitud' => -63.1821,
                'foto' => $foto,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.estado', 'reportada');

        $incidencia = Incidencia::query()->sole();
        Storage::disk('public')->assertExists($incidencia->foto_path);
    }

    public function test_empty_payload_returns_422_and_does_not_create_incident(): void
    {
        $response = $this->postJson('/api/incidencias');

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'categoria_id',
                'descripcion',
                'latitud',
                'longitud',
            ])
            ->assertJsonPath('errors.descripcion.0', 'La descripción es obligatoria.');

        $this->assertDatabaseCount('incidencias', 0);
    }

    #[DataProvider('invalidCoordinates')]
    public function test_invalid_coordinates_return_422(string $field, float $value): void
    {
        $categoria = Categoria::factory()->create();
        $payload = [
            'categoria_id' => $categoria->id,
            'descripcion' => 'Ubicación fuera de rango',
            'latitud' => -17.7833,
            'longitud' => -63.1821,
            $field => $value,
        ];

        $response = $this->postJson('/api/incidencias', $payload);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor($field);

        $this->assertDatabaseCount('incidencias', 0);
    }

    /**
     * @return array<string, array{string, float}>
     */
    public static function invalidCoordinates(): array
    {
        return [
            'latitud menor al mínimo' => ['latitud', -90.01],
            'latitud mayor al máximo' => ['latitud', 90.01],
            'longitud menor al mínimo' => ['longitud', -180.01],
            'longitud mayor al máximo' => ['longitud', 180.01],
        ];
    }

    public function test_unknown_category_returns_422(): void
    {
        $response = $this->postJson('/api/incidencias', [
            'categoria_id' => 999,
            'descripcion' => 'Categoría inexistente',
            'latitud' => -17.7833,
            'longitud' => -63.1821,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('errors.categoria_id.0', 'La categoría seleccionada no existe.');

        $this->assertDatabaseCount('incidencias', 0);
    }

    public function test_non_jpeg_or_png_image_returns_422(): void
    {
        Storage::fake('public');
        $categoria = Categoria::factory()->create();

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->post('/api/incidencias', [
                'categoria_id' => $categoria->id,
                'descripcion' => 'Archivo no permitido',
                'latitud' => -17.7833,
                'longitud' => -63.1821,
                'foto' => UploadedFile::fake()->create('alerta.svg', 100, 'image/svg+xml'),
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('foto');

        $this->assertDatabaseCount('incidencias', 0);
    }

    public function test_lists_incidents_and_returns_requested_detail(): void
    {
        $incidencia = Incidencia::factory()->create([
            'descripcion' => 'Bache profundo en avenida principal',
        ]);

        $this->getJson('/api/incidencias')
            ->assertOk()
            ->assertJsonPath('data.0.id', $incidencia->id)
            ->assertJsonPath('data.0.descripcion', 'Bache profundo en avenida principal');

        $this->getJson("/api/incidencias/{$incidencia->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $incidencia->id);
    }

    public function test_unknown_incident_returns_404(): void
    {
        $response = $this->getJson('/api/incidencias/999');

        $response->assertNotFound();
    }
}
