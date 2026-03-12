<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Eloquent que representa un bien del inventario institucional.
 *
 * Cada registro corresponde a un ítem físico (mobiliario, equipo, etc.) que
 * se desea controlar en el sistema de inventario.
 */
class Bien extends Model
{
    use HasFactory;

    protected $table = 'bienes';

    /**
     * Atributos que se pueden asignar de forma masiva (mass assignment)
     * al crear o actualizar un bien desde formularios.
     */
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'categoria_id',
        'ubicacion_id',
        'estado',
        'fecha_adquisicion',
    ];

    /**
     * Conversión automática de tipos para ciertos campos.
     */
    protected $casts = [
        'fecha_adquisicion' => 'date',
    ];

    public function ubicacionCatalogo(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function categoriaCatalogo(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function getCategoriaNombreAttribute(): ?string
    {
        $nombreCatalogo = $this->relationLoaded('categoriaCatalogo')
            ? $this->categoriaCatalogo?->nombre
            : $this->categoriaCatalogo()->value('nombre');

        return is_string($nombreCatalogo) && trim($nombreCatalogo) !== ''
            ? $nombreCatalogo
            : null;
    }

    public function getUbicacionNombreAttribute(): ?string
    {
        $nombreCatalogo = $this->relationLoaded('ubicacionCatalogo')
            ? $this->ubicacionCatalogo?->nombre
            : $this->ubicacionCatalogo()->value('nombre');

        return is_string($nombreCatalogo) && trim($nombreCatalogo) !== ''
            ? $nombreCatalogo
            : null;
    }
}
