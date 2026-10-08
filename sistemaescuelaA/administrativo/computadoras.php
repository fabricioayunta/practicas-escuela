<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$resultado = mysqli_query(
    $conexion,
    "SELECT c.id_computadora, c.numero_pc, c.estado, l.nombre AS laboratorio
     FROM computadoras c
     INNER JOIN laboratorios l
        ON c.id_laboratorio = l.id_laboratorio
     ORDER BY l.nombre, c.numero_pc"
);

$ok = $_GET["ok"] ?? "";

$titulo = "Computadoras";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Computadoras</h1>
    <p>Todas las computadoras de la escuela.</p>
</div>

<?php if ($ok === "creada") { ?>
    <div class="alerta alerta-exito">La computadora se agregó correctamente.</div>
<?php } elseif ($ok === "editada") { ?>
    <div class="alerta alerta-exito">La computadora se actualizó correctamente.</div>
<?php } elseif ($ok === "eliminada") { ?>
    <div class="alerta alerta-exito">La computadora se eliminó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_computadora.php">+ Nueva computadora</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Laboratorio</th>
                <th>Computadora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["laboratorio"]); ?></td>
                <td><strong>PC <?php echo e($fila["numero_pc"]); ?></strong></td>
                <td>
                    <?php if ($fila["estado"] === "Alta") { ?>
                        <span class="estado operativa">Operativa</span>
                    <?php } else { ?>
                        <span class="estado fuera-servicio">Fuera de servicio</span>
                    <?php } ?>
                </td>
                <td>
                    <div class="acciones">
                        <a class="btn btn-chico" href="editar_computadora.php?id=<?php echo e($fila["id_computadora"]); ?>">Editar</a>
                        <a class="btn btn-peligro btn-chico"
                           href="eliminar_computadora.php?id=<?php echo e($fila["id_computadora"]); ?>"
                           onclick="return confirm('¿Seguro que desea eliminar esta computadora?');">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
