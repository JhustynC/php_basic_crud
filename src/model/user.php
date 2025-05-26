<?php
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Rol.php';

class User extends Model {
    protected $table = 'usuarios';

    public function __construct() {
        parent::__construct();
    }

    public function getRoles($userId) {
        $sql = "SELECT r.id, r.nombre, r.descripcion 
                FROM roles r 
                JOIN usuario_roles ur ON r.id = ur.rol_id 
                WHERE ur.usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function assignRole($userId, $roleId) {
        $sql = "INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $roleId]);
    }

    public function removeRole($userId, $roleId) {
        $sql = "DELETE FROM usuario_roles WHERE usuario_id = ? AND rol_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $roleId]);
    }
}
?>