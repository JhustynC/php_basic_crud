<?php
require_once __DIR__ . '/Model.php';

class User extends Model {
    protected $table = 'usuarios';

    public function __construct() {
        parent::__construct();
    }


    // Crear un nuevo usuario
    public function create($data) {
        return parent::create($data);
    }

    // Buscar un usuario por id
    public function find($id) {
        return parent::find($id);
    }

    // Actualizar un usuario por id
    public function update($id, $data) {
        return parent::update($id, (array) $data);
    }

    // Eliminar un usuario por id
    public function delete($id) {
        return parent::delete($id);
    }

    // Obtener todos los usuarios
    public function all() {
        return parent::all();
    }

    // Métodos para roles que ya tienes
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
        $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE id = ?";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([$userId]);
        if ($stmtCheck->fetchColumn() == 0) {
            throw new Exception("Usuario con ID $userId no existe.");
        }
    
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