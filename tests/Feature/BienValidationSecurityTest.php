<?php

namespace Tests\Feature;

use App\Models\Bien;
use App\Models\Categoria;
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

        $categoriaId = Categoria::query()->where('nombre', 'Computadoras')->value('id');
        $ubicacionId = Ubicacion::query()->where('nombre', 'Oficina 1')->value('id');

        $this->assertDatabaseHas('bienes', [
            'nombre' => 'Laptop Dell',
            'codigo' => 'BIEN-001',
            'descripcion' => 'Equipo de computo para oficina',
            'categoria_id' => $categoriaId,
            'ubicacion_id' => $ubicacionId,
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
            'categoria_id' => Categoria::query()->updateOrCreate(
                ['nombre' => 'Computadoras'],
                ['estado' => 'activo']
            )->id,
            'ubicacion_id' => $ubicacionA->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-15',
        ]);

        Bien::create([
            'nombre' => 'Impresora Epson',
            'codigo' => 'BIEN-011',
            'descripcion' => 'Impresora multifuncional institucional',
            'categoria_id' => Categoria::query()->updateOrCreate(
                ['nombre' => 'Impresoras'],
                ['estado' => 'activo']
            )->id,
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

    public function test_filtra_bienes_por_categoria_catalogo(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $categoriaA = Categoria::create([
            'nombre' => 'Computadoras',
            'estado' => 'activo',
        ]);

        $categoriaB = Categoria::create([
            'nombre' => 'Impresoras',
            'estado' => 'activo',
        ]);

        Bien::create([
            'nombre' => 'Laptop Dell',
            'codigo' => 'BIEN-020',
            'descripcion' => 'Equipo de computo para oficina',
            'categoria_id' => $categoriaA->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-25',
        ]);

        Bien::create([
            'nombre' => 'Impresora Epson',
            'codigo' => 'BIEN-021',
            'descripcion' => 'Impresora multifuncional institucional',
            'categoria_id' => $categoriaB->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-28',
        ]);

        $response = $this->actingAs($user)->get(route('bienes.index', [
            'categoria' => 'Computadoras',
        ]));

        $response->assertOk();
        $response->assertSee('Laptop Dell');
        $response->assertDontSee('Impresora Epson');
    }

    public function test_canonicaliza_query_de_filtros_vacios(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('bienes.index', [
            'search' => 'filtro',
            'estado' => '',
            'categoria' => '',
            'ubicacion' => '',
            'per_page' => 15,
        ]));

        $response->assertRedirect(route('bienes.index', [
            'search' => 'filtro',
        ]));
    }

    public function test_busqueda_trata_comodines_like_como_texto_literal(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        Bien::create([
            'nombre' => 'Laptop Dell',
            'codigo' => 'BIEN-040',
            'descripcion' => 'Equipo de computo para oficina',
            'categoria_id' => Categoria::query()->updateOrCreate(
                ['nombre' => 'Computadoras'],
                ['estado' => 'activo']
            )->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-02-10',
        ]);

        Bien::create([
            'nombre' => 'Impresora Epson',
            'codigo' => 'BIEN-041',
            'descripcion' => 'Impresora multifuncional institucional',
            'categoria_id' => Categoria::query()->updateOrCreate(
                ['nombre' => 'Impresoras'],
                ['estado' => 'activo']
            )->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-02-11',
        ]);

        $response = $this->actingAs($user)->get(route('bienes.index', [
            'search' => '_',
        ]));

        $response->assertOk();
        $response->assertDontSee('Laptop Dell');
        $response->assertDontSee('Impresora Epson');
    }

    public function test_actualiza_bien_y_persiste_ids_de_categoria_y_ubicacion(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $categoriaInicial = Categoria::create([
            'nombre' => 'Computadoras',
            'estado' => 'activo',
        ]);

        $categoriaNueva = Categoria::create([
            'nombre' => 'Equipo',
            'estado' => 'activo',
        ]);

        $ubicacionInicial = Ubicacion::create([
            'nombre' => 'Oficina 1',
            'estado' => 'activo',
        ]);

        $ubicacionNueva = Ubicacion::create([
            'nombre' => 'Depósito A',
            'estado' => 'activo',
        ]);

        $bien = Bien::create([
            'nombre' => 'Laptop Dell',
            'codigo' => 'BIEN-030',
            'descripcion' => 'Equipo de computo para oficina',
            'categoria_id' => $categoriaInicial->id,
            'ubicacion_id' => $ubicacionInicial->id,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-15',
        ]);

        $response = $this->actingAs($user)->put(route('bienes.update', $bien), [
            'nombre' => 'Impresora Epson',
            'codigo' => 'BIEN-030',
            'descripcion' => 'Impresora multifuncional institucional',
            'categoria' => $categoriaNueva->nombre,
            'ubicacion_id' => $ubicacionNueva->id,
            'estado' => 'regular',
            'fecha_adquisicion' => '2026-02-01',
        ]);

        $response->assertRedirect(route('bienes.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('bienes', [
            'id' => $bien->id,
            'nombre' => 'Impresora Epson',
            'codigo' => 'BIEN-030',
            'categoria_id' => $categoriaNueva->id,
            'ubicacion_id' => $ubicacionNueva->id,
            'estado' => 'regular',
        ]);
    }

    public function test_formulario_editar_muestra_categoria_inactiva_asociada(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $categoriaInactiva = Categoria::create([
            'nombre' => 'Archivo Histórico',
            'estado' => 'inactivo',
        ]);

        $categoriaActiva = Categoria::create([
            'nombre' => 'Computadoras',
            'estado' => 'activo',
        ]);

        $bien = Bien::create([
            'nombre' => 'Servidor Rack',
            'codigo' => 'BIEN-031',
            'descripcion' => 'Servidor para procesamiento interno',
            'categoria_id' => $categoriaInactiva->id,
            'ubicacion_id' => null,
            'estado' => 'bueno',
            'fecha_adquisicion' => '2026-01-10',
        ]);

        $response = $this->actingAs($user)->get(route('bienes.edit', $bien));

        $response->assertOk();
        $response->assertSee($categoriaActiva->nombre);
        $response->assertSee($categoriaInactiva->nombre);
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
