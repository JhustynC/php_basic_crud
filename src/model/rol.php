<?php
class Rol {
    public $id;
    public $nombre;
    public $descripcion;

    public function __construct($id = null, $nombre = '', $descripcion = '') {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }
}
?>