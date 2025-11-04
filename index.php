<?php
require_once __DIR__ . '/middlewares/cors.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Definimos rutas por método
$routes = [
    'POST' => [
        'api/auth/login' => 'controllers/auth/login.php',
        'api/usuarios'   => 'controllers/usuarios/crear.php',

    ],
    'GET' => [
        'api/usuarios'         => 'controllers/usuarios/listar.php',
        'api/usuarios/(\d+)'   => 'controllers/usuarios/obtener.php',
    ],
    'PUT' => [
        'api/usuarios/(\d+)'   => 'controllers/usuarios/actualizar.php',
    ],
    'DELETE' => [
        'api/usuarios/(\d+)'   => 'controllers/usuarios/eliminar.php',
    ]
];

// Función para buscar ruta con regex y parámetros
$found = false;
if (isset($routes[$method])) {
    foreach ($routes[$method] as $pattern => $file) {
        if (preg_match("#^$pattern$#", $uri, $matches)) {
            $found = true;
            // Si hay parámetros (como id), los pasamos al controlador
            $params = array_slice($matches, 1);
            require __DIR__ . '/' . $file;
            break;
        }
    }
}

if (!$found) {
    http_response_code(in_array($uri, array_merge(...array_values($routes))) ? 405 : 404);
    echo json_encode(['error' => $found ? 'Método no permitido' : 'Ruta no encontrada', 'uri' => $uri]);
}
