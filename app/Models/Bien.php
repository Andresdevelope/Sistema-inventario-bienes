<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'categoria',
        'ubicacion',
        'estado',
        'fecha_adquisicion',
        'valor',
    ];

    /**
     * Conversión automática de tipos para ciertos campos.
     */
    protected $casts = [
        'fecha_adquisicion' => 'date',
        'valor' => 'decimal:2',
    ];
}
