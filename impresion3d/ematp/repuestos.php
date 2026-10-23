<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 3) {
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
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Stock de repuestos</h1>
    <p>Actualice la cantidad cuando use o reciba repuestos. Para agregar o eliminar repuestos, consulte con el administrador.</p>
</div>

<?php if ($ok === "stock") { ?>
    <div class="alerta alerta-exito">El stock se actualizó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
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
                    <a class="btn btn-chico" href="editar_stock.php?id=<?php echo e($fila["id_repuesto"]); ?>">Actualizar stock</a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
