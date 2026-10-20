<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$resultado = mysqli_query(
    $conexion,
    "SELECT
        articulos.*,
        articulos.cantidad - COALESCE(SUM(prestamos_articulos.cantidad), 0) AS disponibles
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

$titulo = "Artículos";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Artículos</h1>
    <p>Artículos del pañol. Para dar de alta, editar o dar de baja, consulte con el administrador.</p>
</div>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_prestamo.php">+ Nuevo préstamo</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Disponibles</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="5">Todavía no hay artículos cargados.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><strong><?php echo e($fila["nombre"]); ?></strong></td>
                <td><?php echo e($fila["descripcion"] ?? ""); ?></td>
                <td><?php echo e($fila["cantidad"]); ?></td>
                <td><?php echo e($fila["disponibles"]); ?></td>
                <td>
                    <?php if ($fila["estado"] === "Alta") { ?>
                        <span class="estado operativa">Alta</span>
                    <?php } else { ?>
                        <span class="estado fuera-servicio">Baja</span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
