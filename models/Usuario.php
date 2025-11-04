<?php
require_once __DIR__ . '/../db/conexion.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // LOGIN (  username o email)
    public function login($identificador, $password) {
        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM usuarios u
                LEFT JOIN roles r ON u.rol_id = r.id
                WHERE (u.email = :identificador OR u.username = :identificador)
                  AND u.activo = 1
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['identificador' => $identificador]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); 
            return $user;
        }
        return false;
    }

    // CREAR USUARIO
    public function crear($nombre, $username, $email, $password, $rol_id) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios 
                    (nombre_completo, username, email, password, rol_id, activo, created_at, updated_at)
                VALUES (:nombre, :username, :email, :password, :rol_id, 1, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nombre'    => $nombre,
            'username'  => $username,
            'email'     => $email,
            'password'  => $hash,
            'rol_id'    => $rol_id
        ]);
    }

    // LISTAR TODOS LOS USUARIOS
    public function listar() {
        $sql = "SELECT u.id, u.nombre_completo, u.username, u.email, u.rol_id, r.nombre AS rol_nombre,
                       u.activo, u.created_at, u.updated_at
                FROM usuarios u
                LEFT JOIN roles r ON u.rol_id = r.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER UN USUARIO POR ID
    public function obtener($id) {
        $sql = "SELECT u.id, u.nombre_completo, u.username, u.email, u.rol_id, r.nombre AS rol_nombre,
                       u.activo, u.created_at, u.updated_at
                FROM usuarios u
                LEFT JOIN roles r ON u.rol_id = r.id
                WHERE u.id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR USUARIO
    public function actualizar($id, $nombre, $username, $email, $rol_id, $activo, $password = null) {
        $params = [
            'id'       => $id,
            'nombre'   => $nombre,
            'username' => $username,
            'email'    => $email,
            'rol_id'   => $rol_id,
            'activo'   => $activo
        ];

        $sql = "UPDATE usuarios 
                   SET nombre_completo = :nombre, 
                       username = :username, 
                       email = :email, 
                       rol_id = :rol_id, 
                       activo = :activo, 
                       updated_at = NOW()";

        if ($password) {
            $sql .= ", password = :password";
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ELIMINAR USUARIO
    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
