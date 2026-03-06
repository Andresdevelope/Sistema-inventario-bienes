<?php

namespace Tests\Feature;

use App\Models\Bien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BienValidationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_rechaza_nombre_aleatorio_sin_sentido(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $payload = $this->validPayload([
            'nombre' => 'osajdoasjdomasdmoasojfoidfojoaasdjaodjsoa',
        ]);

        $response = $this->actingAs($user)->post(route('bienes.store'), $payload);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseCount('bienes', 0);
    }

    public function test_rechaza_ubicacion_alfanumerica_aleatoria(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $payload = $this->validPayload([
            'ubicacion' => 'Q93024u90123ui49012u4901',
        ]);

        $response = $this->actingAs($user)->post(route('bienes.store'), $payload);

        $response->assertSessionHasErrors(['ubicacion']);
        $this->assertDatabaseCount('bienes', 0);
    }

    public function test_rechaza_codigo_sin_numeros(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $payload = $this->validPayload([
            'codigo' => 'CODIGO',
        ]);

        $response = $this->actingAs($user)->post(route('bienes.store'), $payload);

        $response->assertSessionHasErrors(['codigo']);
        $this->assertDatabaseCount('bienes', 0);
    }

    public function test_guarda_bien_valido_correctamente(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $payload = $this->validPayload();

        $response = $this->actingAs($user)->post(route('bienes.store'), $payload);

        $response->assertRedirect(route('bienes.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bienes', [
            'nombre' => 'Laptop Dell',
            'codigo' => 'BIEN-001',
            'descripcion' => 'Equipo de computo para oficina',
            'categoria' => 'Computadoras',
            'ubicacion' => 'Oficina 1',
            'estado' => 'bueno',
        ]);

        $this->assertSame(1, Bien::count());
    }

    public function test_permite_estado_dado_de_baja(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $payload = $this->validPayload([
            'codigo' => 'BIEN-002',
            'estado' => 'de_baja',
        ]);

        $response = $this->actingAs($user)->post(route('bienes.store'), $payload);

        $response->assertRedirect(route('bienes.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bienes', [
            'codigo' => 'BIEN-002',
            'estado' => 'de_baja',
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'nombre' => 'Laptop Dell',
            'codigo' => 'BIEN-001',
            'descripcion' => 'Equipo de computo para oficina',
            'categoria' => 'Computadoras',
            'ubicacion' => 'Oficina 1',
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-15',
        ], $overrides);
    }
}
