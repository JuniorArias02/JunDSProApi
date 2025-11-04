<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Usuario.php';

$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID requerido en la URL'
    ]);
    exit;
}

$usuario = new Usuario($pdo);
$user = $usuario->obtener($id);

if ($user) {
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no encontrado'
    ]);
}
