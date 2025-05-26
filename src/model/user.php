<?php
class User {
  private $id;
  private $nombre;
  private $email;
  private $roles = [];

  public function __construct($id, $nombre, $email) {
      $this->id = $id;
      $this->nombre = $nombre;
      $this->email = $email;
  }

  public function agregarRol(Rol $rol) {
      $this->roles[] = $rol;
  }

  public function obtenerRoles() {
      return $this->roles;
  }
}
?>
