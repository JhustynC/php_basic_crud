<?php
function renderUserForm($roles)
{
?>
    <div class="card mb-4">
    
        <div class="card-body">
            <form id="user-form" method="POST">
                <input type="hidden" id="user-id" name="id" value="">
                <input type="hidden" id="form-action" name="action" value="create">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="invalid-feedback">Este campo es obligatorio.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Este campo es obligatorio.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">
                                <span id="password-label">Contraseña *</span>
                            </label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                            <div class="invalid-feedback">Este campo es obligatorio.</div>
                            <div class="form-text" id="password-help" style="display: none;">
                                Deja en blanco para mantener la contraseña actual
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div id="roles-container">
                                <?php foreach ($roles as $rol): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="roles[]" value="<?= $rol['id'] ?>"
                                            id="rol_<?= $rol['id'] ?>">
                                        <label class="form-check-label" for="rol_<?= $rol['id'] ?>">
                                            <?= htmlspecialchars($rol['nombre'] ?? '') ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        Guardar Usuario
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancel-btn"
                        onclick="resetForm()" style="display: none;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validación visual en tiempo real
        document.getElementById('user-form').addEventListener('input', function(e) {
            const target = e.target;
            if (target.required && !target.value) {
                target.classList.add('is-invalid');
            } else {
                target.classList.remove('is-invalid');
            }
        });

        // Resetear el formulario
        function resetForm() {
            const form = document.getElementById('user-form');
            form.reset();
            document.getElementById('user-id').value = '';
            document.getElementById('form-action').value = 'create';
            // document.getElementById('form-title').textContent = 'Agregar Usuario';
            document.getElementById('submit-btn').textContent = 'Guardar Usuario';
            document.getElementById('cancel-btn').style.display = 'none';
            document.getElementById('password-label').textContent = 'Contraseña *';
            document.getElementById('password-help').style.display = 'none';
            document.getElementById('contrasena').required = true;

            // Limpiar los roles
            const checkboxes = document.querySelectorAll('input[name="roles[]"]');
            checkboxes.forEach(c => c.checked = false);
        }

        // Cargar datos del usuario para editar
        function editUser(userId) {
            const url_edit = `${miPluginAjaxUrlBase}controllers/userController.php?action=get&id=${userId}`; 
            console.log('Intentando acceder a URL para editar:', url_edit); 
            fetch(url_edit)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;

                        document.getElementById('user-id').value = user.id;
                        document.getElementById('nombre').value = user.nombre;
                        document.getElementById('email').value = user.email;
                        document.getElementById('form-action').value = 'update';
                        // document.getElementById('form-title').textContent = 'Editar Usuario';
                        document.getElementById('submit-btn').textContent = 'Actualizar Usuario';
                        document.getElementById('cancel-btn').style.display = 'inline-block';
                        document.getElementById('password-label').textContent = 'Nueva Contraseña';
                        document.getElementById('password-help').style.display = 'block';
                        document.getElementById('contrasena').required = false;

                        // Marcar roles actuales
                        const checkboxes = document.querySelectorAll('input[name="roles[]"]');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = user.roles.includes(parseInt(checkbox.value));
                        });

                        // Scroll al formulario
                        document.getElementById('user-form').scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        Swal.fire('Error', 'Error al cargar usuario: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al cargar usuario', 'error');
                });
        }

        // Enviar el formulario con AJAX
        document.getElementById('user-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const action = formData.get('action');
            const userId = formData.get('id');
            const url = `${miPluginAjaxUrlBase}controllers/userController.php?action=${action}${userId ? '&id=' + userId : ''}`;

            console.log('Intentando acceder a URL:', url); 

            fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Éxito', data.message, 'success');
                        resetForm();
                        reloadUserTable(); 
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al procesar la solicitud', 'error');
                });
        });
    </script>
<?php
}
?>