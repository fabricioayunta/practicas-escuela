<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/funciones_panol.php";

$articulos = articulos_disponibles($conexion);

$titulo = "Pedir un artículo";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Artículos disponibles</h1>
    <p>Elija el artículo que necesita. La devolución la registra el EMATP.</p>
</div>

<div class="barra-acciones">
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Disponibles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (count($articulos) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="3">No hay artículos disponibles en este momento.</td>
            </tr>
        <?php } ?>

        <?php foreach ($articulos as $fila) { ?>
            <tr>
                <td><strong><?php echo e($fila["nombre"]); ?></strong></td>
                <td><?php echo e($fila["disponibles"]); ?></td>
                <td>
                    <a class="btn btn-exito btn-chico"
                       href="crear_prestamo.php?id=<?php echo e($fila["id_articulo"]); ?>">Pedir</a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
