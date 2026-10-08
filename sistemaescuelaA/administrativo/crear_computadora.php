<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$labs = mysqli_query($conexion, "SELECT id_laboratorio, nombre FROM laboratorios ORDER BY nombre");

$titulo = "Nueva computadora";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Nueva computadora</h1>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_computadora.php" method="POST">

        <div class="campo">
            <label for="id_laboratorio">Laboratorio</label>
            <select id="id_laboratorio" name="id_laboratorio" required>
                <option value="">Seleccione un laboratorio</option>
                <?php while ($l = mysqli_fetch_assoc($labs)) { ?>
                    <option value="<?php echo e($l["id_laboratorio"]); ?>"><?php echo e($l["nombre"]); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="campo">
            <label for="numero_pc">Número de computadora</label>
            <input type="number" id="numero_pc" name="numero_pc" min="1" max="99" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar computadora</button>
            <a class="btn btn-secundario" href="computadoras.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
