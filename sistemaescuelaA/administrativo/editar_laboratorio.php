<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_laboratorio, nombre FROM laboratorios WHERE id_laboratorio = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$laboratorio = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$laboratorio) {
    mostrar_error("No se encontró el laboratorio.", "laboratorios.php");
}

$titulo = "Editar laboratorio";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Editar laboratorio</h1>
</div>

<div class="tarjeta">

    <form class="formulario" action="actualizar_laboratorio.php" method="POST">

        <input type="hidden" name="id_laboratorio" value="<?php echo (int)$laboratorio["id_laboratorio"]; ?>">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="50"
                   value="<?php echo e($laboratorio["nombre"]); ?>" autocomplete="off" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar cambios</button>
            <a class="btn btn-secundario" href="laboratorios.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
