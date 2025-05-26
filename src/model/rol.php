<?php
class Rol {
  private $id;
  private $nombre;
  private $descripcion;

  public function __construct($id, $nombre, $descripcion) {
      $this->id = $id;
      $this->nombre = $nombre;
      $this->descripcion = $descripcion;
  }
}
?>