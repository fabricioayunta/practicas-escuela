<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Pedir un insumo";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Pedir un insumo</h1>
    <p>El préstamo queda registrado a su nombre: <?php echo e($_SESSION["pi_nombre"] . " " . $_SESSION["pi_apellido"]); ?>.</p>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_prestamo.php" method="POST">

        <input type="hidden" name="csrf_token" value="<?php echo e(token_formulario("pi_csrf_token")); ?>">

        <div class="campo">
            <label for="insumo">Insumo</label>
            <input type="text" id="insumo" name="insumo" maxlength="100" required>
            <small>Por ejemplo: filamento PLA, boquilla, cama de impresión.</small>
        </div>

        <div class="campo">
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="9999" value="1" required>
        </div>

        <div class="campo">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="3" maxlength="255"></textarea>
            <small>Opcional. Por ejemplo, para qué clase lo necesita.</small>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Pedir insumo</button>
            <a class="btn btn-secundario" href="inicio.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
