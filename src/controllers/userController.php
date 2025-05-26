<?php
require_once __DIR__ . '/../data/userRepository.php';

class UserController {
  private $conexion;

  public function __construct($conexion) {
      $this->conexion = $conexion;
  }

  public function asignarRolesAUsuario($usuarioId) {
      // Recuperar los roles del usuario desde la base de datos
      $consulta = $this->conexion->prepare("
          SELECT r.id, r.nombre, r.descripcion
          FROM roles r
          JOIN usuario_roles ur ON r.id = ur.rol_id
          WHERE ur.usuario_id = :usuario_id
      ");
      $consulta->bindParam(':usuario_id', $usuarioId);
      $consulta->execute();
      $rolesData = $consulta->fetchAll();

      // Crear objetos Rol y asignarlos al usuario
      $usuario = new User($usuarioId, 'Nombre del Usuario', 'email@ejemplo.com');
      foreach ($rolesData as $rolData) {
          $rol = new Rol($rolData['id'], $rolData['nombre'], $rolData['descripcion']);
          $usuario->agregarRol($rol);
      }

      return $usuario;
  }
}
?>
