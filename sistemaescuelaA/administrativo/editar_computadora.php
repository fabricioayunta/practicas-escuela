<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = mysqli_prepare($conexion, "SELECT * FROM computadoras WHERE id_computadora = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$pc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$pc) {
    mostrar_error("No se encontró la computadora.", "computadoras.php");
}

$labs = mysqli_query($conexion, "SELECT id_laboratorio, nombre FROM laboratorios ORDER BY nombre");

$titulo = "Editar computadora";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Editar computadora</h1>
</div>

<div class="tarjeta">

    <form class="formulario" action="actualizar_computadora.php" method="POST">

        <input type="hidden" name="id" value="<?php echo (int)$pc["id_computadora"]; ?>">

        <div class="campo">
            <label for="id_laboratorio">Laboratorio</label>
            <select id="id_laboratorio" name="id_laboratorio" required>
                <?php while ($l = mysqli_fetch_assoc($labs)) { ?>
                    <option value="<?php echo e($l["id_laboratorio"]); ?>"
                        <?php echo (int)$l["id_laboratorio"] === (int)$pc["id_laboratorio"] ? "selected" : ""; ?>>
                        <?php echo e($l["nombre"]); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="campo">
            <label for="numero_pc">Número de computadora</label>
            <input type="number" id="numero_pc" name="numero_pc" min="1" max="99"
                   value="<?php echo e($pc["numero_pc"]); ?>" required>
        </div>

        <div class="campo">
            <label for="estado">Estado</label>
            <select id="estado" name="estado">
                <option value="Alta" <?php echo $pc["estado"] === "Alta" ? "selected" : ""; ?>>Operativa (Alta)</option>
                <option value="Baja" <?php echo $pc["estado"] === "Baja" ? "selected" : ""; ?>>Fuera de servicio (Baja)</option>
            </select>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar cambios</button>
            <a class="btn btn-secundario" href="computadoras.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
