<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id_usuario = (int)$_SESSION["id_usuario"];

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
     WHERE tickets.id_usuario = ?
     ORDER BY tickets.fecha_creacion DESC"
);

mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$ok = $_GET["ok"] ?? "";

$titulo = "Mis tickets";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Mis tickets</h1>
    <p>Estos son los avisos que usted envió.</p>
</div>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">Su ticket fue creado correctamente.</div>
<?php } elseif ($ok === "editado") { ?>
    <div class="alerta alerta-exito">Los cambios del ticket se guardaron correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_ticket.php">+ Crear un ticket</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>N.º</th>
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
                <td class="sin-datos" colspan="7">Todavía no creó ningún ticket.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["id_ticket"]); ?></td>
                <td><?php echo e($fila["laboratorio"] ?? "—"); ?></td>
                <td><?php echo $fila["numero_pc"] !== null ? "PC " . e($fila["numero_pc"]) : "—"; ?></td>
                <td>
                    <strong><?php echo e($fila["titulo"]); ?></strong><br>
                    <?php echo nl2br(e($fila["descripcion"])); ?>
                </td>
                <td>
                    <span class="estado <?php echo clase_estado($fila["estado"]); ?>">
                        <?php echo e($fila["estado"]); ?>
                    </span>
                </td>
                <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_creacion"])); ?></td>
                <td>
                    <div class="acciones">

                        <?php if (!empty($fila["foto"])) { ?>
                            <a class="btn btn-secundario btn-chico"
                               href="../uploads/tickets/<?php echo e($fila["foto"]); ?>"
                               target="_blank">Ver foto</a>
                        <?php } ?>

                        <?php if ($fila["estado"] === "Abierto") { ?>
                            <a class="btn btn-chico"
                               href="editar_ticket.php?id=<?php echo e($fila["id_ticket"]); ?>">Editar</a>
                        <?php } ?>

                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
