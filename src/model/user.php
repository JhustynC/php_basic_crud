<?php
class User {
    public $id;
    public $nombre;
    public $email;
    public $contrasena;
    public $roles = [];

    public function __construct($id = null, $nombre = '', $email = '', $contrasena = '') {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->contrasena = $contrasena;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
    }

    public function addRole($rolId) {
        if (!in_array($rolId, $this->roles)) {
            $this->roles[] = $rolId;
        }
    }
}
?>