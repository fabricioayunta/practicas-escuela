<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$resultado = mysqli_query($conexion, "SELECT * FROM repuestos ORDER BY nombre");

if (!$resultado) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudieron cargar los repuestos.", "inicio.php");
}

$ok = $_GET["ok"] ?? "";

$titulo = "Repuestos";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Stock de repuestos</h1>
    <p>Repuestos de las impresoras 3D y la cantidad disponible de cada uno.</p>
</div>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">El repuesto se creó correctamente.</div>
<?php } elseif ($ok === "editado") { ?>
    <div class="alerta alerta-exito">El repuesto se actualizó correctamente.</div>
<?php } elseif ($ok === "eliminado") { ?>
    <div class="alerta alerta-exito">El repuesto se eliminó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_repuesto.php">+ Nuevo repuesto</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="4">Todavía no hay repuestos cargados.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><strong><?php echo e($fila["nombre"]); ?></strong></td>
                <td><?php echo e($fila["descripcion"] ?? ""); ?></td>
                <td>
                    <?php if ((int)$fila["cantidad"] === 0) { ?>
                        <span class="estado fuera-servicio">Sin stock</span>
                    <?php } else { ?>
                        <?php echo e($fila["cantidad"]); ?>
                    <?php } ?>
                </td>
                <td>
                    <div class="acciones">
                        <a class="btn btn-chico" href="editar_repuesto.php?id=<?php echo e($fila["id_repuesto"]); ?>">Editar</a>
                        <a class="btn btn-peligro btn-chico"
                           href="eliminar_repuesto.php?id=<?php echo e($fila["id_repuesto"]); ?>"
                           onclick="return confirm('¿Seguro que desea eliminar este repuesto?');">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
