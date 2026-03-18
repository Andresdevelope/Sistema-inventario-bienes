<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Seguridad HTTP / TLS
    |--------------------------------------------------------------------------
    |
    | force_https: fuerza generación de URLs HTTPS en producción o cuando
    |              la variable se active explícitamente.
    | headers_enabled: habilita cabeceras de seguridad HTTP.
    | hsts_enabled: habilita Strict-Transport-Security cuando la petición
    |               llega por HTTPS.
    |
    */
    'force_https' => env('FORCE_HTTPS', false),
    'headers_enabled' => env('SECURITY_HEADERS_ENABLED', true),
    'hsts_enabled' => env('SECURITY_HSTS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Recuperación de contraseña
    |--------------------------------------------------------------------------
    |
    | recovery_token_ttl_minutes: minutos de validez del token enviado por
    | correo tras validar preguntas de seguridad.
    |
    */
    'recovery_token_ttl_minutes' => env('RECOVERY_TOKEN_TTL_MINUTES', 1),

    // Máximo de intentos permitidos para validar el token de recuperación (por usuario/sesión)
    'recovery_token_max_attempts' => env('RECOVERY_TOKEN_MAX_ATTEMPTS', 3),

    // Tiempo mínimo (en segundos) entre solicitudes de token de recuperación (rate limit)
    'recovery_token_request_interval' => env('RECOVERY_TOKEN_REQUEST_INTERVAL', 60),

    // Máximo de reenvíos permitidos del token dentro del mismo proceso de recuperación
    'recovery_token_max_resends' => env('RECOVERY_TOKEN_MAX_RESENDS', 3),
];
