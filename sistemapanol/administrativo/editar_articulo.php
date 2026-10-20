<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de artículo inválido.", "articulos.php");
}

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_articulo, nombre, descripcion, cantidad FROM articulos WHERE id_articulo = ?"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo cargar el artículo.", "articulos.php");
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo cargar el artículo.", "articulos.php");
}

$articulo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$articulo) {
    mostrar_error("No se encontró el artículo.", "articulos.php");
}

$titulo = "Editar artículo";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Editar artículo</h1>
</div>

<div class="tarjeta">

    <form class="formulario" action="actualizar_articulo.php" method="POST">

        <input type="hidden" name="id_articulo" value="<?php echo (int)$articulo["id_articulo"]; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo e(token_formulario("panol_csrf_token")); ?>">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="100"
                   value="<?php echo e($articulo["nombre"]); ?>" required>
        </div>

        <div class="campo">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" maxlength="255"
                   value="<?php echo e($articulo["descripcion"] ?? ""); ?>">
            <small>Opcional.</small>
        </div>

        <div class="campo">
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="9999"
                   value="<?php echo e($articulo["cantidad"]); ?>" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar cambios</button>
            <a class="btn btn-secundario" href="articulos.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
