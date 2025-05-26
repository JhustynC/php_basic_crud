<?php
require_once __DIR__ . '/../data/userRepository.php';

class UserController {
  private $usuarioRepository;

  public function __construct($conn) {
    $this->usuarioRepository = new UserRepository($conn);
  }

  public function manejarSolicitud() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['email'])) {
      $this->usuarioRepository->insertarUsuario($_POST['nombre'], $_POST['email']);
    }

    if (isset($_GET['delete'])) {
      $this->usuarioRepository->eliminarUsuario($_GET['delete']);
    }

    return $this->usuarioRepository->obtenerUsuarios();
  }
}
?>
