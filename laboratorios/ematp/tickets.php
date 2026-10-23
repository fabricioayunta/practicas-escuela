<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Primero los abiertos, después los pendientes y al final los cerrados */
$sql = "SELECT
            tickets.id_ticket,
            tickets.titulo,
            tickets.estado,
            tickets.fecha_creacion,
            usuarios.nombre,
            usuarios.apellido,
            computadoras.numero_pc,
            laboratorios.nombre AS laboratorio
        FROM tickets
        INNER JOIN usuarios
            ON tickets.id_usuario = usuarios.id_usuario
        LEFT JOIN computadoras
            ON tickets.id_computadora = computadoras.id_computadora
        LEFT JOIN laboratorios
            ON computadoras.id_laboratorio = laboratorios.id_laboratorio
        ORDER BY FIELD(tickets.estado, 'Abierto', 'Pendiente', 'Cerrado'),
                 tickets.fecha_creacion DESC";

$resultado = mysqli_query($conexion, $sql);

$titulo = "Tickets";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Tickets</h1>
    <p>Avisos enviados por los profesores. Los abiertos aparecen primero.</p>
</div>

<div class="barra-acciones">
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>N.º</th>
                <th>Profesor</th>
                <th>Laboratorio</th>
                <th>PC</th>
                <th>Problema</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="8">No hay tickets por el momento.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["id_ticket"]); ?></td>
                <td><?php echo e($fila["nombre"] . " " . $fila["apellido"]); ?></td>
                <td><?php echo e($fila["laboratorio"] ?? "—"); ?></td>
                <td><?php echo $fila["numero_pc"] !== null ? "PC " . e($fila["numero_pc"]) : "—"; ?></td>
                <td><?php echo e($fila["titulo"]); ?></td>
                <td>
                    <span class="estado <?php echo clase_estado($fila["estado"]); ?>">
                        <?php echo e($fila["estado"]); ?>
                    </span>
                </td>
                <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_creacion"])); ?></td>
                <td>
                    <a class="btn btn-chico" href="editar_ticket.php?id=<?php echo e($fila["id_ticket"]); ?>">Gestionar</a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
