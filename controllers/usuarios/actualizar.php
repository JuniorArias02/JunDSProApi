<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../models/Usuario.php';

$id = $params[0] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID requerido en la URL']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$nombre   = $data['nombre_completo'] ?? '';
$username = $data['username'] ?? '';
$email    = $data['email'] ?? '';
$rol_id   = $data['rol'] ?? 2;
$activo   = $data['activo'] ?? 1;
$password = $data['password'] ?? null;

$usuario = new Usuario($pdo);
$ok = $usuario->actualizar($id, $nombre, $username, $email, $rol_id, $activo, $password);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Usuario actualizado con éxito']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el usuario']);
}
