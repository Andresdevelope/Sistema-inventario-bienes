# Hashing de contraseñas: bcrypt y Argon2 (Laravel)

## Objetivo
Explicar cómo se almacenan contraseñas de forma segura con algoritmos de hash resistentes a ataques, justificar el uso de `bcrypt` y `Argon2` en cumplimiento de buenas prácticas (OWASP, NIST) y mostrar cómo configurarlo en Laravel.

## Conceptos clave
- Hash de contraseñas: Transformación irreversible que protege contraseñas almacenadas.
- Salt: Valor aleatorio único por contraseña. Evita tablas rainbow y colisiones entre usuarios.
- Coste/Trabajo: Parámetro que controla el tiempo de cómputo del hash para frenar ataques por fuerza bruta.
- Resistencia a hardware: Argon2 es "memory-hard"; dificulta ataques con GPUs/ASICs aumentando uso de memoria.

## bcrypt
- Algoritmo probado y ampliamente soportado.
- Incluye salt por defecto.
- Parámetro de coste: `rounds` (factor logarítmico). En Laravel se controla con `BCRYPT_ROUNDS`.
- Buenas prácticas OWASP: coste entre 10 y 14 según rendimiento del servidor. En local suele ser 12.
- Cumplimiento:
  - OWASP Password Storage Cheat Sheet: bcrypt es recomendado.
  - NIST SP 800-63B (5.1.1.2): se recomiendan funciones específicas para contraseñas con parámetros de trabajo ajustables; bcrypt cumple.

## Argon2 (Argon2i / Argon2id)
- Ganador del Password Hashing Competition (PHC).
- "Memory-hard": además de tiempo, usa memoria para reducir ventaja de GPUs/ASICs.
- Variante recomendada: **Argon2id** (defensa híbrida frente a ataques por canal lateral y GPU).
- Parámetros principales:
  - `time_cost` (iteraciones)
  - `memory_cost` (memoria en KiB)
  - `threads` (paralelismo)
- Valores de partida razonables (ajustar midiendo en tu servidor):
  - `time_cost`: 3
  - `memory_cost`: 65536 (64 MB)
  - `threads`: 2
- Cumplimiento:
  - OWASP: Argon2id recomendado en entornos modernos.
  - NIST: exige funciones con parámetros de trabajo configurables; Argon2id cumple y mejora resistencia.

## Laravel: configuración y uso
Laravel soporta `bcrypt`, `argon` y `argon2id`. El hash se usa con `Hash::make($password)` y se verifica con `Hash::check($plain, $hashed)`.

### Variables de entorno
- `.env` (tu proyecto ya incluye):
  - `BCRYPT_ROUNDS=12` (coste por defecto de bcrypt)

### Configuración opcional (`config/hashing.php`)
Si necesitas personalizar o forzar un driver, crea `config/hashing.php` con:

```php
<?php

return [
    'driver' => env('HASH_DRIVER', 'bcrypt'),

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
    ],

    'argon' => [
        'memory' => env('ARGON_MEMORY', 65536), // KiB
        'threads' => env('ARGON_THREADS', 2),
        'time' => env('ARGON_TIME', 3),
    ],

    'argon2id' => [
        'memory' => env('ARGON2ID_MEMORY', 65536), // KiB
        'threads' => env('ARGON2ID_THREADS', 2),
        'time' => env('ARGON2ID_TIME', 3),
    ],
];
```

Y en `.env` puedes alternar:

```
HASH_DRIVER=bcrypt
# o
# HASH_DRIVER=argon2id
```

### ¿Cuál elegir?
- Si buscas compatibilidad y rendimiento estable: **bcrypt** con `BCRYPT_ROUNDS=12` es adecuado.
- Si priorizas mayor resistencia frente a hardware moderno (GPU/ASIC): **Argon2id** con parámetros calibrados.

Tip: calibra los parámetros para ~100-300 ms por hash en tu servidor (login/registro) y ajusta si cambia el hardware.

## Justificación de cumplimiento
- OWASP Password Storage Cheat Sheet: recomienda bcrypt, scrypt, PBKDF2, Argon2id; exige sal aleatoria, parámetros configurables y almacenamiento seguro.
- NIST SP 800-63B 5.1.1.2: exige funciones específicas para contraseñas con parámetros de trabajo; recomienda evitar funciones rápidas como SHA-256 sin KDF.
- Laravel utiliza `password_hash` de PHP y genera sal aleatoria automática; los parámetros se configuran vía entorno.

## Buenas prácticas complementarias
- Políticas de contraseña (longitud mínima, bloqueo tras intentos, etc.).
- Protección contra enumeración de usuarios y rate limiting.
- 2FA/MFA para cuentas sensibles.
- Mantener `APP_KEY` seguro; opcionalmente añade "pepper" (secreto global) si el modelo de amenaza lo exige.

## Referencias
- OWASP: Password Storage Cheat Sheet
- NIST SP 800-63B (Digital Identity Guidelines), sección 5.1.1.2
- PHC: Argon2 paper y especificación
