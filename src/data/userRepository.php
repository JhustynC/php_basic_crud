<?php
require_once __DIR__ . '/../config/database.php';

class UserRepository {
  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function obtenerUsuarios() {
    $sql = "SELECT * FROM usuarios";
    return $this->conn->query($sql);
  }

  public function insertarUsuario($nombre, $email) {
    $nombre = $this->conn->real_escape_string($nombre);
    $email = $this->conn->real_escape_string($email);
    $sql = "INSERT INTO usuarios (nombre, email) VALUES ('$nombre', '$email')";
    return $this->conn->query($sql);
  }

  public function eliminarUsuario($id) {
    $id = (int)$id;
    $sql = "DELETE FROM usuarios WHERE id = $id";
    return $this->conn->query($sql);
  }
}
?>
