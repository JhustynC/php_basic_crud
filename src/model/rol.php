<?php
require_once __DIR__ . '/Model.php';

class Rol extends Model {
    protected $table = 'roles';

    public function __construct() {
        parent::__construct();
    }
}
?>