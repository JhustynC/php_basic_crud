<?php
function renderUserTable($users, $roles) {
    // Crear un mapa de roles para fácil acceso
    $roleMap = [];
    foreach ($roles as $rol) {
        $roleMap[$rol->id] = $rol->nombre;
    }
    ?>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Lista de Usuarios</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay usuarios registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user->id ?></td>
                                    <td><?= htmlspecialchars($user->nombre) ?></td>
                                    <td><?= htmlspecialchars($user->email) ?></td>
                                    <td>
                                        <?php if (empty($user->roles)): ?>
                                            <span class="text-muted">Sin roles</span>
                                        <?php else: ?>
                                            <?php 
                                            $userRoleNames = [];
                                            foreach ($user->roles as $roleId) {
                                                if (isset($roleMap[$roleId])) {
                                                    $userRoleNames[] = $roleMap[$roleId];
                                                }
                                            }
                                            ?>
                                            <?php foreach ($userRoleNames as $index => $roleName): ?>
                                                <span class="badge bg-primary me-1">
                                                    <?= htmlspecialchars($roleName) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary" 
                                                    onclick="editUser(<?= $user->id ?>)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="confirmDelete(<?= $user->id ?>, '<?= htmlspecialchars($user->nombre) ?>')" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId, userName) {
            if (confirm(`¿Estás seguro de que deseas eliminar al usuario "${userName}"?`)) {
                window.location.href = `controllers/userController.php?action=delete&id=${userId}`;
            }
        }
    </script>
    <?php
}
?>