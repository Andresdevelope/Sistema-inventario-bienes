<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogoValidationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_rechaza_renombre_categoria_con_texto_sin_sentido(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $categoria = Categoria::create([
            'nombre' => 'Computadoras',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->put(route('bienes.categorias.update', $categoria), [
            'nombre' => 'AdsadasioanfiadfnionfioafihFioahfioahf',
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Computadoras',
        ]);
    }

    public function test_rechaza_renombre_ubicacion_con_texto_sin_sentido(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $ubicacion = Ubicacion::create([
            'nombre' => 'Oficina 1',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->put(route('bienes.ubicaciones.update', $ubicacion), [
            'nombre' => 'AdsadasioanfiadfnionfioafihFIOAHFIOAHF',
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseHas('ubicaciones', [
            'id' => $ubicacion->id,
            'nombre' => 'Oficina 1',
        ]);
    }

    public function test_rechaza_renombre_ubicacion_con_bloque_numerico_excesivo(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $ubicacion = Ubicacion::create([
            'nombre' => 'Laboratorio 2',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->put(route('bienes.ubicaciones.update', $ubicacion), [
            'nombre' => '21312312312313123123211231241234',
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseHas('ubicaciones', [
            'id' => $ubicacion->id,
            'nombre' => 'Laboratorio 2',
        ]);
    }
}
