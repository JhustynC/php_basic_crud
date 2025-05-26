<form action="guardar_usuario.php" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="email">Correo electrónico:</label>
    <input type="email" id="email" name="email" required>

    <label for="roles">Roles:</label>
    <select id="roles" name="roles[]" multiple>
        <?php
        // Recuperar todos los roles desde la base de datos
        $consulta = $conexion->query("SELECT id, nombre FROM roles");
        while ($rol = $consulta->fetch()) {
            echo "<option value='{$rol['id']}'>{$rol['nombre']}</option>";
        }
        ?>
    </select>

    <button type="submit">Guardar</button>
</form>
