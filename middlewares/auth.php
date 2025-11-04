<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../utils/jwt.php';

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
    exit;
}

$token = $matches[1];
$userData = verificarToken($token);



if (!$userData) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido o expirado']);
    exit;
}

// Aquí lo guardamos para middlewares y controladores
$GLOBALS['user'] = $userData;

