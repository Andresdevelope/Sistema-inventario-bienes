<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Constantes de roles para evitar usar strings "mágicos".
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERADOR = 'user'; // "user" actúa como operador del sistema

    /**
     * Permisos granulares del sistema.
     */
    public const PERMISSIONS = [
        'bienes.ver',
        'bienes.crear',
        'bienes.editar',
        'bienes.eliminar',
        'categorias.ver',
        'categorias.gestionar',
        'reportes.exportar',
    ];

    /**
     * Permisos por defecto del operador para mantener compatibilidad.
     */
    public const DEFAULT_OPERADOR_PERMISSIONS = [
        'bienes.ver',
        'bienes.crear',
        'bienes.editar',
        'bienes.eliminar',
        'categorias.ver',
        'categorias.gestionar',
        'reportes.exportar',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'security_color_answer',
        'security_animal_answer',
        'security_padre_answer',
        'login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token'
        
        ,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locked_until' => 'datetime',
            'permissions' => 'array',
        ];
    }

    /**
     * Indica si el usuario es administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Indica si el usuario es operador (rol "user" u otro alias equivalente).
     */
    public function isOperador(): bool
    {
        return in_array($this->role, [self::ROLE_OPERADOR, 'operador'], true);
    }

    /**
     * Lista de permisos disponibles para asignación.
     *
     * @return list<string>
     */
    public static function availablePermissions(): array
    {
        return self::PERMISSIONS;
    }

    /**
     * Permisos por defecto para rol operador.
     *
     * @return list<string>
     */
    public static function defaultOperadorPermissions(): array
    {
        return self::DEFAULT_OPERADOR_PERMISSIONS;
    }

    /**
     * Resuelve permisos efectivos del usuario.
     *
     * @return list<string>
     */
    public function resolvedPermissions(): array
    {
        if ($this->isAdmin()) {
            return self::availablePermissions();
        }

        $available = self::availablePermissions();
        $current = $this->permissions;

        if (!is_array($current) || $current === []) {
            return self::defaultOperadorPermissions();
        }

        return array_values(array_intersect($current, $available));
    }

    /**
     * Evalúa si el usuario tiene un permiso específico.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($permission, $this->resolvedPermissions(), true);
    }
}
