<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$resultado = mysqli_query(
    $conexion,
    "SELECT
        articulos.*,
        COUNT(prestamos_articulos.id_prestamo) AS pendientes
     FROM articulos
     LEFT JOIN prestamos_articulos
        ON prestamos_articulos.id_articulo = articulos.id_articulo
        AND prestamos_articulos.estado = 'Pendiente'
     GROUP BY articulos.id_articulo
     ORDER BY articulos.nombre"
);

if (!$resultado) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudieron cargar los artículos.", "inicio.php");
}

$error = $_GET["error"] ?? "";
$ok    = $_GET["ok"] ?? "";

$titulo = "Artículos";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Artículos</h1>
    <p>Herramientas y elementos que se pueden prestar.</p>
</div>

<?php if ($error === "tiene_prestamos") { ?>
    <div class="alerta alerta-error">
        No se puede eliminar un artículo que tiene préstamos registrados.
        Si ya no se usa, déselo de baja.
    </div>
<?php } elseif ($error === "prestado") { ?>
    <div class="alerta alerta-error">
        No se puede dar de baja un artículo que tiene préstamos sin devolver.
        Primero registre la devolución.
    </div>
<?php } ?>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">El artículo se creó correctamente.</div>
<?php } elseif ($ok === "editado") { ?>
    <div class="alerta alerta-exito">El artículo se actualizó correctamente.</div>
<?php } elseif ($ok === "alta") { ?>
    <div class="alerta alerta-exito">El artículo se dio de alta correctamente.</div>
<?php } elseif ($ok === "baja") { ?>
    <div class="alerta alerta-exito">El artículo se dio de baja correctamente.</div>
<?php } elseif ($ok === "eliminado") { ?>
    <div class="alerta alerta-exito">El artículo se eliminó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_articulo.php">+ Nuevo artículo</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Prestados</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="6">Todavía no hay artículos cargados.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><strong><?php echo e($fila["nombre"]); ?></strong></td>
                <td><?php echo e($fila["descripcion"] ?? ""); ?></td>
                <td><?php echo e($fila["cantidad"]); ?></td>
                <td><?php echo e($fila["pendientes"]); ?></td>
                <td>
                    <?php if ($fila["estado"] === "Alta") { ?>
                        <span class="estado operativa">Alta</span>
                    <?php } else { ?>
                        <span class="estado fuera-servicio">Baja</span>
                    <?php } ?>
                </td>
                <td>
                    <div class="acciones">
                        <a class="btn btn-chico" href="editar_articulo.php?id=<?php echo e($fila["id_articulo"]); ?>">Editar</a>

                        <?php if ($fila["estado"] === "Alta") { ?>
                            <a class="btn btn-secundario btn-chico"
                               href="cambiar_estado_articulo.php?id=<?php echo e($fila["id_articulo"]); ?>&estado=Baja"
                               onclick="return confirm('¿Seguro que desea dar de baja este artículo?');">Dar de baja</a>
                        <?php } else { ?>
                            <a class="btn btn-exito btn-chico"
                               href="cambiar_estado_articulo.php?id=<?php echo e($fila["id_articulo"]); ?>&estado=Alta"
                               onclick="return confirm('¿Seguro que desea dar de alta este artículo?');">Dar de alta</a>
                        <?php } ?>

                        <a class="btn btn-peligro btn-chico"
                           href="eliminar_articulo.php?id=<?php echo e($fila["id_articulo"]); ?>"
                           onclick="return confirm('¿Seguro que desea eliminar este artículo?');">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
