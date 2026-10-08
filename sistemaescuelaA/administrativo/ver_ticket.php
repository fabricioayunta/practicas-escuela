<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id = (int)($_GET["id"] ?? 0);

/* Datos del ticket */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT
        tickets.*,
        usuarios.nombre,
        usuarios.apellido,
        laboratorios.nombre AS laboratorio,
        computadoras.numero_pc,
        ematp.nombre AS nombre_ematp,
        ematp.apellido AS apellido_ematp
     FROM tickets
     INNER JOIN usuarios
        ON tickets.id_usuario = usuarios.id_usuario
     LEFT JOIN computadoras
        ON tickets.id_computadora = computadoras.id_computadora
     LEFT JOIN laboratorios
        ON computadoras.id_laboratorio = laboratorios.id_laboratorio
     LEFT JOIN usuarios ematp
        ON tickets.id_ematp_asignado = ematp.id_usuario
     WHERE tickets.id_ticket = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$ticket = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$ticket) {
    mostrar_error("No se encontró el ticket.", "tickets.php");
}

/* Historial */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT
        historialticket.*,
        usuarios.nombre,
        usuarios.apellido
     FROM historialticket
     INNER JOIN usuarios
        ON historialticket.id_usuario = usuarios.id_usuario
     WHERE historialticket.id_ticket = ?
     ORDER BY historialticket.fecha DESC"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$historial = mysqli_stmt_get_result($stmt);

$titulo = "Ver ticket";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Ticket n.º <?php echo e($ticket["id_ticket"]); ?></h1>
    <p>
        <span class="estado <?php echo clase_estado($ticket["estado"]); ?>"><?php echo e($ticket["estado"]); ?></span>
    </p>
</div>

<div class="barra-acciones">
    <a class="btn btn-secundario" href="tickets.php">← Volver a los tickets</a>
</div>

<div class="tarjeta">

    <h2 style="margin-top:0;">Información general</h2>

    <div class="tabla-responsive" style="box-shadow:none;">
        <table class="ficha">
            <tr><td>Profesor</td><td><?php echo e($ticket["nombre"] . " " . $ticket["apellido"]); ?></td></tr>
            <tr><td>Laboratorio</td><td><?php echo e($ticket["laboratorio"] ?? "—"); ?></td></tr>
            <tr><td>Computadora</td><td><?php echo $ticket["numero_pc"] !== null ? "PC " . e($ticket["numero_pc"]) : "—"; ?></td></tr>
            <tr><td>Componentes con problemas</td><td><?php echo e($ticket["componentes_afectados"] ?? "—"); ?></td></tr>
            <tr><td>Fecha</td><td><?php echo date("d/m/Y H:i", strtotime($ticket["fecha_creacion"])); ?></td></tr>
            <tr>
                <td>EMATP asignado</td>
                <td>
                    <?php
                    if (!empty($ticket["nombre_ematp"])) {
                        echo e($ticket["nombre_ematp"] . " " . $ticket["apellido_ematp"]);
                    } else {
                        echo "Sin asignar";
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <h3>Descripción</h3>
    <p class="texto-ticket"><?php echo nl2br(e($ticket["descripcion"])); ?></p>

    <?php if (!empty($ticket["foto"])) { ?>
        <h3 style="margin-top:1.2em;">Foto del problema</h3>
        <a href="../uploads/tickets/<?php echo e($ticket["foto"]); ?>" target="_blank">
            <img class="foto-ticket" src="../uploads/tickets/<?php echo e($ticket["foto"]); ?>" alt="Foto del problema">
        </a>
        <small>Haga clic en la foto para verla en tamaño completo.</small>
    <?php } ?>

</div>

<h2>Historial del ticket</h2>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($historial) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="4">Todavía no hay movimientos.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($historial)) { ?>
            <tr>
                <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha"])); ?></td>
                <td><?php echo e($fila["nombre"] . " " . $fila["apellido"]); ?></td>
                <td>
                    <span class="estado <?php echo clase_estado($fila["estado"]); ?>"><?php echo e($fila["estado"]); ?></span>
                </td>
                <td><?php echo nl2br(e($fila["observacion"])); ?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
