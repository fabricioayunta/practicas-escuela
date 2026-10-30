<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

// Establecemos la zona horaria predeterminada
date_default_timezone_set('America/Argentina/Buenos_Aires');

$titulo = "Nueva entrega de insumo";
$menu   = "ematp";

$fecha_actual = date("Y-m-d");
$hora_actual  = date("H:i");

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Nueva entrega de insumo</h1>
    <p>Queda pendiente hasta que se registre la devolución.</p>
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
            <label for="persona">Entregado a</label>
            <input type="text" id="persona" name="persona" maxlength="100" required>
            <small>Nombre y apellido de quien se lleva el insumo.</small>
        </div>

        <div class="campo">
            <label for="fecha_prestamo">Fecha y hora de entrega</label>
            <input type="date" id="fecha_prestamo" name="fecha_prestamo" value="<?php echo $fecha_actual; ?>" required style="margin-bottom: 8px;">
            <input type="time" id="hora_prestamo" name="hora_prestamo" value="<?php echo $hora_actual; ?>" required>
        </div>

        <div class="campo">
            <label for="fecha_devolucion">Fecha y hora estimada de devolución</label>
            <input type="date" id="fecha_devolucion" name="fecha_devolucion" value="<?php echo $fecha_actual; ?>" required style="margin-bottom: 8px;">
            <input type="time" id="hora_devolucion" name="hora_devolucion" value="<?php echo $hora_actual; ?>" required>
            <small>Se registrará para el control de la devolución en la tabla.</small>
        </div>

        <div class="campo">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="3" maxlength="255"></textarea>
            <small>Opcional.</small>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar entrega</button>
            <a class="btn btn-secundario" href="prestamos.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>