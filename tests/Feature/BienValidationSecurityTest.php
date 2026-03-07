<?php

namespace Tests\Feature;

use App\Models\Bien;
use App\Models\Ubicacion;
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

    public function test_guarda_bien_con_ubicacion_id_del_catalogo(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $ubicacion = Ubicacion::create([
            'nombre' => 'Laboratorio 2',
            'estado' => 'activo',
        ]);

        $payload = $this->validPayload([
            'codigo' => 'BIEN-003',
            'ubicacion' => null,
            'ubicacion_id' => $ubicacion->id,
        ]);

        $response = $this->actingAs($user)->post(route('bienes.store'), $payload);

        $response->assertRedirect(route('bienes.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bienes', [
            'codigo' => 'BIEN-003',
            'ubicacion' => 'Laboratorio 2',
            'ubicacion_id' => $ubicacion->id,
        ]);
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

    public function test_filtra_bienes_por_ubicacion(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $ubicacionA = Ubicacion::create([
            'nombre' => 'Oficina 1',
            'estado' => 'activo',
        ]);

        $ubicacionB = Ubicacion::create([
            'nombre' => 'Depósito A',
            'estado' => 'activo',
        ]);

        Bien::create([
            'nombre' => 'Laptop Dell',
            'codigo' => 'BIEN-010',
            'descripcion' => 'Equipo de computo para oficina',
            'categoria' => 'Computadoras',
            'ubicacion' => $ubicacionA->nombre,
            'ubicacion_id' => $ubicacionA->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-15',
        ]);

        Bien::create([
            'nombre' => 'Impresora Epson',
            'codigo' => 'BIEN-011',
            'descripcion' => 'Impresora multifuncional institucional',
            'categoria' => 'Impresoras',
            'ubicacion' => $ubicacionB->nombre,
            'ubicacion_id' => $ubicacionB->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-20',
        ]);

        $response = $this->actingAs($user)->get(route('bienes.index', [
            'ubicacion' => $ubicacionA->id,
        ]));

        $response->assertOk();
        $response->assertSee('Laptop Dell');
        $response->assertDontSee('Impresora Epson');
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
