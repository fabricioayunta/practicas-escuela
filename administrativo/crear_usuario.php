<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Módulo en el que estamos (2 = Máquinas de Impresión 3D): viene marcado por defecto */
$moduloActual = 2;

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

        <input type="hidden" name="csrf_token" value="<?php echo e(token_formulario("pi_csrf_token")); ?>">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="50"
                   pattern="[^&quot;'´]+" title="No se permiten comillas en el nombre."
                   required>
        </div>

        <div class="campo">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" maxlength="50"
                   pattern="[^&quot;'´]+" title="No se permiten comillas en el apellido."
                   required>
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
                <?php foreach (roles_sistema() as $numero => $nombreRol) { ?>
                    <option value="<?php echo $numero; ?>" <?php echo $numero === 2 ? "selected" : ""; ?>>
                        <?php echo e($nombreRol); ?>
                    </option>
                <?php } ?>
            </select>
            <small>El rol es el mismo en todos los módulos.</small>
        </div>

        <?php casillas_modulos([$moduloActual]); ?>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Crear usuario</button>
            <a class="btn btn-secundario" href="usuarios.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
