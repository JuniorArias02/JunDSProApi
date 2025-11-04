<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../vendor/autoload.php';

function generarToken($datos) {
    $secret = $_ENV['JWT_SECRET'];
    $exp = time() + ($_ENV['JWT_EXPIRE'] ?? 3600);

    $payload = [
        'iat' => time(),
        'exp' => $exp,       
        'data' => $datos
    ];

    return JWT::encode($payload, $secret, 'HS256');
}

function verificarToken($token) {
    try {
        $secret = $_ENV['JWT_SECRET'];
        $decoded = JWT::decode($token, new Key($secret, 'HS256'));
        return (array) $decoded->data;
    } catch (Exception $e) {
        return false;
    }
}
