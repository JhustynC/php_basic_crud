<?php
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/rol.php';

class UserRepository {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getAllUsers() {
        $users = [];
        $sql = "SELECT u.id, u.nombre, u.email, u.contrasena FROM usuarios u ORDER BY u.id";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new User($row['id'], $row['nombre'], $row['email'], $row['contrasena']);
                $user->setRoles($this->getUserRoles($row['id']));
                $users[] = $user;
            }
        }
        return $users;
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new User($row['id'], $row['nombre'], $row['email'], $row['contrasena']);
            $user->setRoles($this->getUserRoles($row['id']));
            return $user;
        }
        return null;
    }

    public function createUser(User $user) {
        $this->conn->begin_transaction();
        
        try {
            // Verificar si el email ya existe
            $checkSql = "SELECT id FROM usuarios WHERE email = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bind_param("s", $user->email);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                throw new Exception("El email ya está registrado");
            }
            
            $hashedPassword = password_hash($user->contrasena, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $user->nombre, $user->email, $hashedPassword);
            
            if ($stmt->execute()) {
                $userId = $this->conn->insert_id;
                $this->updateUserRoles($userId, $user->roles);
                $this->conn->commit();
                return $userId;
            } else {
                throw new Exception("Error al insertar usuario: " . $this->conn->error);
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function updateUser(User $user) {
        $this->conn->begin_transaction();
        
        try {
            // Verificar si el email ya existe en otro usuario
            $checkSql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bind_param("si", $user->email, $user->id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                throw new Exception("El email ya está registrado por otro usuario");
            }
            
            if (!empty($user->contrasena)) {
                $hashedPassword = password_hash($user->contrasena, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nombre = ?, email = ?, contrasena = ? WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("sssi", $user->nombre, $user->email, $hashedPassword, $user->id);
            } else {
                $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ssi", $user->nombre, $user->email, $user->id);
            }
            
            if ($stmt->execute()) {
                $this->updateUserRoles($user->id, $user->roles);
                $this->conn->commit();
                return true;
            } else {
                throw new Exception("Error al actualizar usuario: " . $this->conn->error);
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getAllRoles() {
        $roles = [];
        $sql = "SELECT * FROM roles ORDER BY nombre";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $roles[] = new Rol($row['id'], $row['nombre'], $row['descripcion']);
            }
        }
        return $roles;
    }

    private function getUserRoles($userId) {
        $roles = [];
        $sql = "SELECT rol_id FROM usuario_roles WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row['rol_id'];
        }
        return $roles;
    }

    private function updateUserRoles($userId, $roles) {
        // Eliminar roles existentes
        $sql = "DELETE FROM usuario_roles WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        // Insertar nuevos roles
        if (!empty($roles)) {
            $sql = "INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            foreach ($roles as $rolId) {
                $stmt->bind_param("ii", $userId, $rolId);
                $stmt->execute();
            }
        }
    }
}
?>