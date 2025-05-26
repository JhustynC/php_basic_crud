<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../data/userRepository.php';

class UserController {
    private $userRepository;

    public function __construct() {
        global $conn;
        $this->userRepository = new UserRepository($conn);
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'create':
                $this->createUser();
                break;
            case 'update':
                $this->updateUser();
                break;
            case 'delete':
                $this->deleteUser();
                break;
            case 'get':
                $this->getUserForEdit();
                break;
            default:
                $this->listUsers();
                break;
        }
    }

    private function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user = new User();
                $user->nombre = trim($_POST['nombre'] ?? '');
                $user->email = trim($_POST['email'] ?? '');
                $user->contrasena = $_POST['contrasena'] ?? '';
                $user->roles = $_POST['roles'] ?? [];

                if (empty($user->nombre) || empty($user->email) || empty($user->contrasena)) {
                    throw new Exception('Todos los campos son obligatorios');
                }

                if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El formato del email no es válido');
                }

                $userId = $this->userRepository->createUser($user);
                
                if ($userId) {
                    header('Location: ../index.php?success=Usuario creado exitosamente');
                    exit;
                } else {
                    throw new Exception('Error al crear usuario');
                }
            } catch (Exception $e) {
                header('Location: ../index.php?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    private function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user = new User();
                $user->id = intval($_POST['id'] ?? 0);
                $user->nombre = trim($_POST['nombre'] ?? '');
                $user->email = trim($_POST['email'] ?? '');
                $user->contrasena = $_POST['contrasena'] ?? '';
                $user->roles = $_POST['roles'] ?? [];

                if ($user->id <= 0) {
                    throw new Exception('ID de usuario inválido');
                }

                if (empty($user->nombre) || empty($user->email)) {
                    throw new Exception('Nombre y email son obligatorios');
                }

                if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('El formato del email no es válido');
                }

                if ($this->userRepository->updateUser($user)) {
                    header('Location: ../index.php?success=Usuario actualizado exitosamente');
                    exit;
                } else {
                    throw new Exception('Error al actualizar usuario');
                }
            } catch (Exception $e) {
                header('Location: ../index.php?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    private function deleteUser() {
        $id = intval($_GET['id'] ?? 0);
        
        try {
            if ($id <= 0) {
                throw new Exception('ID de usuario inválido');
            }
            
            if ($this->userRepository->deleteUser($id)) {
                header('Location: ../index.php?success=Usuario eliminado exitosamente');
            } else {
                header('Location: ../index.php?error=Error al eliminar usuario');
            }
        } catch (Exception $e) {
            header('Location: ../index.php?error=' . urlencode($e->getMessage()));
        }
        exit;
    }

    private function getUserForEdit() {
        $id = $_GET['id'] ?? 0;
        header('Content-Type: application/json');
        
        try {
            $user = $this->userRepository->getUserById($id);
            if ($user) {
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'nombre' => $user->nombre,
                        'email' => $user->email,
                        'roles' => $user->roles
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    private function listUsers() {
        // Esta función se maneja en index.php
    }

    public function getAllUsers() {
        return $this->userRepository->getAllUsers();
    }

    public function getAllRoles() {
        return $this->userRepository->getAllRoles();
    }
}

// Ejecutar el controlador cuando se accede directamente al archivo
if (basename($_SERVER['PHP_SELF']) === 'userController.php') {
    $controller = new UserController();
    $controller->handleRequest();
}
?>