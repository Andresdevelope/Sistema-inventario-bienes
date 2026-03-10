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
];
