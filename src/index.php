<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/userController.php';

$controller = new UserController($conn);
$usuarios = $controller->manejarSolicitud();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>CRUD Básico en PHP</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <?php include_once __DIR__ . '/../ui/formulario.php'; ?>
    <?php include_once __DIR__ . '/../ui/tabla.php'; ?>
  </div>
</body>
</html>
