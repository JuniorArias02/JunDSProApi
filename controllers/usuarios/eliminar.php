<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/permiso.php';
require_once __DIR__ . '/../../models/Usuario.php';

requirePermission('usuarios:eliminar');

$usuario = new Usuario($pdo);


$id = $params[0] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID requerido en la URL']);
    exit;
}

if ($usuario->eliminar($id)) {
    echo json_encode(['message' => 'Usuario eliminado correctamente']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se pudo eliminar el usuario']);
}
