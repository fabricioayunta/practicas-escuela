<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id_usuario = (int)$_SESSION["panol_id_usuario"];

$stmt = mysqli_prepare(
    $conexion,
    "SELECT p.*, a.nombre AS articulo
     FROM prestamos_articulos p
     INNER JOIN articulos a
        ON p.id_articulo = a.id_articulo
     WHERE p.id_usuario = ?
     ORDER BY FIELD(p.estado, 'Pendiente', 'Devuelto'), p.fecha_prestamo DESC"
);

mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$ok = $_GET["ok"] ?? "";

$titulo = "Mis préstamos";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Mis préstamos</h1>
    <p>Estos son los artículos que usted pidió.</p>
</div>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">Su préstamo se registró correctamente. Retire el artículo en el pañol.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="articulos.php">+ Pedir un artículo</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>N.º</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Fecha préstamo</th>
                <th>Fecha devolución</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="6">Todavía no pidió ningún artículo.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["id_prestamo"]); ?></td>
                <td>
                    <strong><?php echo e($fila["articulo"]); ?></strong>
                    <?php if (!empty($fila["observaciones"])) { ?>
                        <br><small><?php echo e($fila["observaciones"]); ?></small>
                    <?php } ?>
                </td>
                <td><?php echo e($fila["cantidad"]); ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_prestamo"])); ?></td>
                <td><?php echo $fila["fecha_devolucion"] !== null ? date("d/m/Y H:i", strtotime($fila["fecha_devolucion"])) : "—"; ?></td>
                <td>
                    <span class="estado <?php echo clase_prestamo($fila["estado"]); ?>">
                        <?php echo e($fila["estado"]); ?>
                    </span>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
