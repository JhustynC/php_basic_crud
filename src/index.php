<?php
require_once 'data/userRepository.php';
$userRepo = new UserRepository();
$users = $userRepo->getAllUsers();
$roles = $userRepo->getAllRoles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Tus estilos acá */
        .card-header { background-color: #f8f9fa; }
        .form-check { margin-bottom: 0.5rem; }
        .btn-primary { transition: background-color 0.3s; }
        .btn-primary:hover { background-color: #0056b3; }
        .badge { font-size: 0.9rem; padding: 0.4em 0.6em; }
        .btn-group .btn { padding: 0.3rem 0.6rem; }
        .table-hover tbody tr:hover { background-color: #f1f3f5; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php
        include 'ui/userForm.php';
        renderUserForm($roles);
        ?>
        
        <!-- Aquí mostramos la tabla de usuarios -->
        <div id="user-table-container">
            <?php
            include 'ui/userTable.php';
            renderUserTable($users, $roles);
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        //? Función para recargar la tabla desde PHP sin recargar la página
        function reloadUserTable() {
            fetch('ui/reloadUserTable.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('user-table-container').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error al recargar la tabla:', error);
                });
        }
    </script>
</body>
</html>
