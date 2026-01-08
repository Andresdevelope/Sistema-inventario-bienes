<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de Cross-Origin Resource Sharing (CORS)
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar las cabeceras de Cross-Origin Resource Sharing
    | (CORS). Laravel ya incluye una librería para manejar esto.
    |
    | Para más información: https://github.com/fruitcake/laravel-cors
    |
    */

    'paths' => ['api/*'], // Rutas que aplicarán estas reglas de CORS.

    'allowed_methods' => ['*'], // Métodos HTTP permitidos (GET, POST, etc.). '*' para todos.

    /*
     | Orígenes permitidos. Especifica aquí los dominios que pueden acceder a tu API.
     | Por seguridad, nunca uses ['*'] en producción.
     | Ejemplo para desarrollo local: ['http://localhost:8080', 'http://127.0.0.1:8080']
     | Ejemplo para producción: ['https://tu-dominio-frontend.com']
     */
    'allowed_origins' => [],

    /*
     | Patrones de orígenes permitidos. Útil para subdominios.
     | Ejemplo: ['https://*.tu-dominio.com']
     */
    'allowed_origins_patterns' => [],

    // Cabeceras HTTP permitidas en la petición. '*' para todas.
    'allowed_headers' => ['*'],

    // Cabeceras expuestas al navegador en la respuesta.
    'exposed_headers' => [],

    // Tiempo en segundos que el navegador cachea la respuesta de preflight (OPTIONS). 0 para deshabilitar.
    'max_age' => 0,

    // Define si se permiten credenciales (cookies, cabeceras de autorización) en peticiones cross-origin.
    'supports_credentials' => false,

];
