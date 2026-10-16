<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Token de seguridad del formulario */
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de usuario inválido.", "usuarios.php");
}

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_usuario, nombre, apellido, email, id_rol
     FROM usuarios
     WHERE id_usuario = ?
     LIMIT 1"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo cargar el usuario.", "usuarios.php");
}

mysqli_stmt_bind_param($stmt, "i", $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo cargar el usuario.", "usuarios.php");
}

$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$usuario) {
    mostrar_error("No se encontró el usuario.", "usuarios.php");
}

$roles = [1 => "Administrativo", 2 => "Profesor", 3 => "EMATP"];

$titulo = "Editar usuario";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Editar usuario</h1>
</div>

<div class="tarjeta">

    <form class="formulario" action="actualizar_usuario.php" method="POST">

        <input type="hidden" name="id_usuario" value="<?php echo (int)$usuario["id_usuario"]; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION["csrf_token"]); ?>">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="50"
                   value="<?php echo e($usuario["nombre"]); ?>"
                   pattern="[^&quot;'´]+" title="No se permiten comillas en el nombre."
                   required>
        </div>

        <div class="campo">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" maxlength="50"
                   value="<?php echo e($usuario["apellido"]); ?>"
                   pattern="[^&quot;'´]+" title="No se permiten comillas en el apellido."
                   required>
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="100"
                   value="<?php echo e($usuario["email"]); ?>" required>
        </div>

        <div class="campo">
            <label for="contrasena">Nueva contraseña</label>
            <input type="password" id="contrasena" name="contrasena" minlength="8" maxlength="255"
                   autocomplete="new-password">
            <small>Déjela vacía si quiere mantener la contraseña actual.</small>
        </div>

        <div class="campo">
            <label for="id_rol">Rol</label>
            <select id="id_rol" name="id_rol" required>
                <?php foreach ($roles as $numero => $nombreRol) { ?>
                    <option value="<?php echo $numero; ?>" <?php echo (int)$usuario["id_rol"] === $numero ? "selected" : ""; ?>>
                        <?php echo e($nombreRol); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar cambios</button>
            <a class="btn btn-secundario" href="usuarios.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
