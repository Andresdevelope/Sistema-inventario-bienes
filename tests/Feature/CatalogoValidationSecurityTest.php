<?php

namespace Tests\Feature;

use App\Models\Bien;
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

    public function test_rechaza_categoria_con_patron_repetitivo_asdasdas(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $categoria = Categoria::create([
            'nombre' => 'Computadoras',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->put(route('bienes.categorias.update', $categoria), [
            'nombre' => 'Asdasdasdasdasda',
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Computadoras',
        ]);
    }

    public function test_rechaza_categoria_con_repeticion_interna_larga(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $categoria = Categoria::create([
            'nombre' => 'Mobiliario',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->put(route('bienes.categorias.update', $categoria), [
            'nombre' => 'Asdsoadjkoadjkoask',
        ]);

        $response->assertSessionHasErrors(['nombre']);
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Mobiliario',
        ]);
    }

    public function test_elimina_categoria_sin_bienes_asociados(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $categoria = Categoria::create([
            'nombre' => 'Archivo Temporal',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->delete(route('bienes.categorias.destroy', $categoria));

        $response->assertRedirect(route('bienes.categorias.index'));
        $this->assertDatabaseMissing('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Archivo Temporal',
        ]);
    }

    public function test_no_elimina_categoria_con_bienes_asociados(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $categoria = Categoria::create([
            'nombre' => 'Mobiliario',
            'estado' => 'activo',
        ]);

        Bien::create([
            'nombre' => 'Silla ejecutiva',
            'codigo' => 'MOB-001',
            'descripcion' => 'Silla para oficina principal',
            'categoria' => 'Mobiliario',
            'ubicacion' => 'Oficina 1',
            'estado' => 'bueno',
        ]);

        $response = $this->actingAs($user)->delete(route('bienes.categorias.destroy', $categoria));

        $response->assertRedirect(route('bienes.categorias.index'));
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Mobiliario',
        ]);
    }

    public function test_elimina_ubicacion_sin_bienes_asociados(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $ubicacion = Ubicacion::create([
            'nombre' => 'Bodega Temporal',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($user)->delete(route('bienes.ubicaciones.destroy', $ubicacion));

        $response->assertRedirect(route('bienes.categorias.index', ['tab' => 'ubicaciones']));
        $this->assertDatabaseMissing('ubicaciones', [
            'id' => $ubicacion->id,
            'nombre' => 'Bodega Temporal',
        ]);
    }

    public function test_no_elimina_ubicacion_con_bienes_asociados(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $ubicacion = Ubicacion::create([
            'nombre' => 'Laboratorio 3',
            'estado' => 'activo',
        ]);

        Bien::create([
            'nombre' => 'Microscopio',
            'codigo' => 'LAB-001',
            'descripcion' => 'Equipo de laboratorio',
            'categoria' => 'Equipos',
            'ubicacion' => 'Laboratorio 3',
            'ubicacion_id' => $ubicacion->id,
            'estado' => 'bueno',
        ]);

        $response = $this->actingAs($user)->delete(route('bienes.ubicaciones.destroy', $ubicacion));

        $response->assertRedirect(route('bienes.categorias.index', ['tab' => 'ubicaciones']));
        $this->assertDatabaseHas('ubicaciones', [
            'id' => $ubicacion->id,
            'nombre' => 'Laboratorio 3',
        ]);
    }
}
