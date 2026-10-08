<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id_ticket  = (int)($_GET["id"] ?? 0);
$id_usuario = (int)$_SESSION["id_usuario"];


/* Buscar el ticket (solo si es del profesor y sigue abierto) */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT
        tickets.*,
        computadoras.numero_pc,
        laboratorios.nombre AS laboratorio
     FROM tickets
     LEFT JOIN computadoras
        ON tickets.id_computadora = computadoras.id_computadora
     LEFT JOIN laboratorios
        ON computadoras.id_laboratorio = laboratorios.id_laboratorio
     WHERE tickets.id_ticket = ?
       AND tickets.id_usuario = ?
       AND tickets.estado = 'Abierto'"
);

mysqli_stmt_bind_param($stmt, "ii", $id_ticket, $id_usuario);
mysqli_stmt_execute($stmt);

$ticket = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$ticket) {
    mostrar_error("No puede editar este ticket. Solo se pueden editar sus propios tickets mientras están abiertos.", "mis_tickets.php");
}

$componentesActuales = array_filter(explode(", ", $ticket["componentes_afectados"] ?? ""));

$titulo = "Editar ticket";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Editar el ticket n.º <?php echo e($ticket["id_ticket"]); ?></h1>
    <p>
        <?php echo e($ticket["laboratorio"] ?? "—"); ?>
        <?php if ($ticket["numero_pc"] !== null) { ?>
            · PC <?php echo e($ticket["numero_pc"]); ?>
        <?php } ?>
    </p>
</div>

<div class="tarjeta">

    <form class="formulario" action="actualizar_ticket.php" method="POST">

        <input type="hidden" name="id_ticket" value="<?php echo e($ticket["id_ticket"]); ?>">

        <h2 style="margin-top:0;">¿Qué parte tiene el problema?</h2>

        <?php casillas_componentes($componentesActuales); ?>

        <div class="campo">
            <label for="observacion">Descripción del problema</label>
            <textarea id="observacion" name="observacion" required><?php echo e($ticket["descripcion"]); ?></textarea>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar cambios</button>
            <a class="btn btn-secundario" href="mis_tickets.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
