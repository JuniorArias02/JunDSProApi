<?php
require_once __DIR__ . '/auth.php';

function requirePermission($permiso) {
    global $pdo;
    $userId = $GLOBALS['user']['id'];

    $sql = "SELECT p.nombre
            FROM permisos p
            JOIN rol_permiso rp ON rp.permiso_id = p.id
            JOIN usuarios u ON u.rol_id = rp.rol_id
            WHERE u.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $userId]);
    $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array($permiso, $permisos)) {
        http_response_code(403);
        echo json_encode(['error' => '❌ No tienes permiso para esta acción.']);
        exit();
    }
}
