<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Rol.php';

class UserRepository {
    private $userModel;
    private $rolModel;

    public function __construct() {
        $this->userModel = new User();
        $this->rolModel = new Rol();
    }

    public function getAllUsers() {
        $users = $this->userModel->all();
        foreach ($users as &$user) {
            $user['roles'] = $this->userModel->getRoles($user['id']);
        }
        return $users;
    }

    public function findUser($id) {
        $user = $this->userModel->find($id);
        if ($user) {
            $user['roles'] = $this->userModel->getRoles($id);
        }
        return $user;
    }

    public function createUser($data) {
        if (!isset($data['contrasena'])) {
            $data['contrasena'] = password_hash('default123', PASSWORD_BCRYPT); // Contraseña por defecto
        } else {
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_BCRYPT);
        }
        $userId = $this->userModel->create($data);
        if (isset($data['roles']) && is_array($data['roles'])) {
            foreach ($data['roles'] as $roleId) {
                $this->userModel->assignRole($userId, $roleId);
            }
        }
        return $userId;
    }

    public function updateUser($id, $data) {
        $updateData = [
            'nombre' => $data['nombre'],
            'email' => $data['email']
        ];
        if (isset($data['contrasena']) && !empty($data['contrasena'])) {
            $updateData['contrasena'] = password_hash($data['contrasena'], PASSWORD_BCRYPT);
        }
        $this->userModel->update($id, $updateData);
        if (isset($data['roles']) && is_array($data['roles'])) {
            $currentRoles = array_column($this->userModel->getRoles($id), 'id');
            $newRoles = $data['roles'];
            foreach (array_diff($currentRoles, $newRoles) as $roleId) {
                $this->userModel->removeRole($id, $roleId);
            }
            foreach (array_diff($newRoles, $currentRoles) as $roleId) {
                $this->userModel->assignRole($id, $roleId);
            }
        }
    }

    public function deleteUser($id) {
        return $this->userModel->delete($id); // Las relaciones se eliminan automáticamente por ON DELETE CASCADE
    }

    public function getAllRoles() {
        return $this->rolModel->all();
    }
}
?>