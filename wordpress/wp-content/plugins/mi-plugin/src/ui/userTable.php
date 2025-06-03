<?php
function renderUserTable($users, $roles)
{
    // Crear un mapa de roles para fácil acceso
    $roleMap = [];
    foreach ($roles as $rol) {
        $roleMap[$rol['id']] = $rol['nombre'];  // Esto ya está correcto
    }
?>
    <div class="card">
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
                                <tr data-user-id="<?= $user['id'] ?? '' ?>">
                                    <td><?= $user['id'] ?? 'N/A' ?></td>
                                    <td><?= htmlspecialchars($user['nombre'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                    <td>
                                        <?php
                                        if (empty($user['roles']) || !is_array($user['roles'])): ?>
                                            <span class="text-muted">Sin roles</span>
                                        <?php else: ?>
                                            <?php
                                            $userRoleNames = [];
                                            foreach ($user['roles'] as $role) {
                                                if (isset($role['id']) && isset($roleMap[$role['id']])) {
                                                    $userRoleNames[] = $roleMap[$role['id']];
                                                }
                                            }
                                            foreach ($userRoleNames as $roleName): ?>
                                                <span class="badge bg-primary me-1">
                                                    <?= htmlspecialchars($roleName) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick="editUser(<?= $user['id'] ?? '' ?>)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="confirmDelete(<?= $user['id'] ?? '' ?>, '<?= htmlspecialchars($user['nombre'] ?? '') ?>')"
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
            Swal.fire({
                title: `¿Eliminar a "${userName}"?`,
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteUrl = `${miPluginAjaxUrlBase}controllers/userController.php?action=delete&id=${userId}`;
                    console.log('Intentando eliminar con URL:', deleteUrl); // <-- LÍNEA DE DEPURACIÓN
                    fetch(deleteUrl, {
                            method: 'GET' // Consider changing to 'DELETE' or 'POST' for delete operations as per REST best practices
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Eliminado', data.message, 'success');
                                const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                                if (row) row.remove();
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Error al eliminar usuario', 'error');
                        });
                }
            });
        }
    </script>
<?php
}
?>