<?php
require_once __DIR__ . '/../../middlewares/cors.php';
require_once __DIR__ . '/../../db/conexion.php';
require_once __DIR__ . '/../../middlewares/auth.php';
require_once __DIR__ . '/../../middlewares/permiso.php';
require_once __DIR__ . '/../../models/Usuario.php';


requirePermission('usuario:crear');

$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre_completo'];
$username = $data['username'];
$email = $data['email'];
$password = $data['password'];
$rol_id = $data['rol_id'];

$usuario = new Usuario($pdo);
$ok = $usuario->crear($nombre, $username, $email, $password, $rol_id);

if ($ok) {
    echo json_encode([
        'success' => true,
        'message' => 'Usuario creado con éxito'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo crear el usuario'
    ]);
}
