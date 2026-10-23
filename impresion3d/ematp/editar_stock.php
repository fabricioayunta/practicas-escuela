<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de repuesto inválido.", "repuestos.php");
}

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_repuesto, nombre, descripcion, cantidad FROM repuestos WHERE id_repuesto = ?"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo cargar el repuesto.", "repuestos.php");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$repuesto = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$repuesto) {
    mostrar_error("No se encontró el repuesto.", "repuestos.php");
}

$titulo = "Actualizar stock";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Actualizar stock</h1>
    <p><?php echo e($repuesto["nombre"]); ?></p>
</div>

<div class="tarjeta">

    <form class="formulario" action="actualizar_stock.php" method="POST">

        <input type="hidden" name="id_repuesto" value="<?php echo (int)$repuesto["id_repuesto"]; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo e(token_formulario("pi_csrf_token")); ?>">

        <div class="campo">
            <label for="cantidad">Cantidad disponible</label>
            <input type="number" id="cantidad" name="cantidad" min="0" max="9999"
                   value="<?php echo e($repuesto["cantidad"]); ?>" required>
            <small>Escriba cuántas unidades quedan disponibles.</small>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar stock</button>
            <a class="btn btn-secundario" href="repuestos.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
