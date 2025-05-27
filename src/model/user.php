<?php
require_once __DIR__ . '/Model.php';

class User extends Model {
    protected $table = 'usuarios';

    public function __construct() {
        parent::__construct();
    }

    // Crear un nuevo usuario
    public function create($data) {
        $sql = "INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['contrasena']
        ]);
        if ($result) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    // Buscar un usuario por id
    public function find($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Actualizar un usuario por id
    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id; // para el WHERE

        $sql = "UPDATE usuarios SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // Eliminar un usuario por id
    public function delete($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Obtener todos los usuarios
    public function all() {
        $sql = "SELECT * FROM usuarios";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
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