<?php
require_once __DIR__ . '/data/userRepository.php';
$userRepo = new UserRepository();
$users = $userRepo->getAllUsers();
$roles = $userRepo->getAllRoles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestión de Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap, FontAwesome y SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const miPluginAjaxUrlBase = '<?php echo plugin_dir_url(__FILE__); ?>'; // Points to .../wp-content/plugins/mi-plugin/src/
    </script>

    <style>
        body {
            background-color: #f1f3f5;
        }

        .container {
            max-width: 1000px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border: none;
            border-radius: 12px;
        }

        .card-header {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn i {
            margin-right: 4px;
        }

        .badge {
            font-size: 0.85rem;
        }

        h1 {
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <div class="container mt-5 mb-5">
        <h1 class="text-center text-primary text-uppercase mb-4">Gestión de Usuarios</h1>

        <!-- Tarjeta del formulario -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa fa-user-plus"></i> Crear o Editar Usuario
            </div>
            <div class="card-body">
                <?php
                include __DIR__ . '/ui/userForm.php';
                renderUserForm($roles);
                ?>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-users"></i> Lista de Usuarios
            </div>
            <div class="card-body p-0">
                <div id="user-table-container">
                    <?php
                    include __DIR__ . '/ui/userTable.php';
                    renderUserTable($users, $roles);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function reloadUserTable() {
            fetch(`${miPluginAjaxUrlBase}ui/reloadUserTable.php`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('user-table-container').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error al recargar la tabla:', error);
                    Swal.fire('Error', 'No se pudo cargar la tabla', 'error');
                });
        }
    </script>
</body>
</html>
