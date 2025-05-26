<?php
require_once '../data/userRepository.php';
header('Content-Type: application/json');

$userRepo = new UserRepository();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'get' && isset($_GET['id'])) {
        $user = $userRepo->findUser($_GET['id']);
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    } elseif ($action == 'delete' && isset($_GET['id'])) {
        $success = $userRepo->deleteUser($_GET['id']);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario']);
        }
    } elseif ($action == 'create' || $action == 'update') {
        $data = $_POST;
        if ($action == 'create') {
            $userId = $userRepo->createUser($data);
            if ($userId) {
                echo json_encode(['success' => true, 'message' => 'Usuario creado']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear usuario']);
            }
        } elseif ($action == 'update' && isset($_GET['id'])) {
            $success = $userRepo->updateUser($_GET['id'], $data);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Usuario actualizado']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario']);
            }
        }
    }
}
?>