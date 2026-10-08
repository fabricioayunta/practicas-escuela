<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Crear usuario";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Crear usuario</h1>
    <p>Complete todos los datos de la nueva persona.</p>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_usuario.php" method="POST">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="50" required>
        </div>

        <div class="campo">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" maxlength="50" required>
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="100" required>
        </div>

        <div class="campo">
            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" minlength="8" maxlength="255"
                   autocomplete="new-password" required>
            <small>Mínimo 8 caracteres.</small>
        </div>

        <div class="campo">
            <label for="id_rol">Rol</label>
            <select id="id_rol" name="id_rol" required>
                <option value="1">Administrativo</option>
                <option value="2" selected>Profesor</option>
                <option value="3">EMATP</option>
            </select>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Crear usuario</button>
            <a class="btn btn-secundario" href="usuarios.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
