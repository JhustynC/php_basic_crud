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
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                                    id="rolesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Seleccionar roles
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="rolesDropdown">
                                    <?php foreach ($roles as $rol): ?>
                                        <li class="dropdown-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="roles[]" value="<?= $rol['id'] ?>"
                                                    id="rol_<?= $rol['id'] ?>">
                                                <label class="form-check-label w-100" for="rol_<?= $rol['id'] ?>">
                                                    <?= htmlspecialchars($rol['nombre'] ?? '') ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div id="selectedRoles" class="mt-2 small text-muted">
                                Ningún rol seleccionado
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
        // Actualizar el texto del botón con los roles seleccionados
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="roles[]"]');
            const dropdownButton = document.getElementById('rolesDropdown');
            const selectedRolesDisplay = document.getElementById('selectedRoles');

            function updateSelectedRoles() {
                const selected = [];
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selected.push(checkbox.nextElementSibling.textContent.trim());
                    }
                });

                if (selected.length > 0) {
                    dropdownButton.textContent = selected.join(', ');
                    selectedRolesDisplay.textContent = selected.join(', ');
                    selectedRolesDisplay.classList.remove('text-muted');
                    selectedRolesDisplay.classList.add('text-primary');
                } else {
                    dropdownButton.textContent = 'Seleccionar roles';
                    selectedRolesDisplay.textContent = 'Ningún rol seleccionado';
                    selectedRolesDisplay.classList.remove('text-primary');
                    selectedRolesDisplay.classList.add('text-muted');
                }
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedRoles);
            });

            // Evitar que el menú se cierre al hacer clic en los checkboxes
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });

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
            document.getElementById('rolesDropdown').textContent = 'Seleccionar roles';
            document.getElementById('selectedRoles').textContent = 'Ningún rol seleccionado';
            document.getElementById('selectedRoles').classList.remove('text-primary');
            document.getElementById('selectedRoles').classList.add('text-muted');
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
                        document.getElementById('rolesDropdown').textContent = user.roles.map(role => role.nombre).join(', ');
                        document.getElementById('selectedRoles').textContent = user.roles.map(role => role.nombre).join(', ');
                        document.getElementById('selectedRoles').classList.remove('text-muted');
                        document.getElementById('selectedRoles').classList.add('text-primary');

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