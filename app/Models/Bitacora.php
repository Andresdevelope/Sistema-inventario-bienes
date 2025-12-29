<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacoras';

    protected $fillable = [
        'user_id',
        'modulo',
        'entidad_id',
        'accion',
        'resultado',
        'descripcion',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registrar un evento en la bitácora de forma sencilla.
     */
    public static function registrar(
        string $modulo,
        string $accion,
        ?int $entidadId = null,
        ?string $descripcion = null,
        string $resultado = 'exito',
    ): void
    {
        try {
            static::create([
                'user_id' => Auth::id(),
                'modulo' => $modulo,
                'accion' => $accion,
                'entidad_id' => $entidadId,
                'resultado' => $resultado,
                'descripcion' => $descripcion,
            ]);
        } catch (\Throwable $e) {
            // Nunca romper el flujo de la app por un error de bitácora.
        }
    }
}
