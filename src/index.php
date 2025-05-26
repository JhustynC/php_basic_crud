<?php
require_once 'config/database.php';
require_once 'controllers/userController.php';
require_once 'ui/userForm.php';
require_once 'ui/userTable.php';

$controller = new UserController();
$users = $controller->getAllUsers();
$roles = $controller->getAllRoles();

// Manejar mensajes de éxito y error
$successMessage = $_GET['success'] ?? '';
$errorMessage = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-container {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .alert {
            border-radius: 10px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn {
            border-radius: 8px;
        }
        .table {
            margin-bottom: 0;
        }
        .badge {
            font-size: 0.75em;
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">
                    <i class="fas fa-users me-2"></i>
                    Sistema de Gestión de Usuarios
                </h1>
                
                <!-- Mensajes de éxito/error -->
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= htmlspecialchars($successMessage) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= htmlspecialchars($errorMessage) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Formulario de Usuario -->
                <?php renderUserForm($roles); ?>

                <!-- Tabla de Usuarios -->
                <?php renderUserTable($users, $roles); ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-ocultar alertas después de 5 segundos
        document.addEventListener('DOMLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Limpiar URL después de mostrar mensajes
        if (window.location.search.includes('success=') || window.location.search.includes('error=')) {
            const url = new URL(window.location);
            url.searchParams.delete('success');
            url.searchParams.delete('error');
            window.history.replaceState({}, document.title, url.pathname);
        }
    </script>
</body>
</html>