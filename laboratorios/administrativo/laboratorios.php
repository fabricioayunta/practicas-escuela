<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$resultado = mysqli_query(
    $conexion,
    "SELECT
        laboratorios.id_laboratorio,
        laboratorios.nombre,
        COUNT(computadoras.id_computadora) AS cantidad_pc
     FROM laboratorios
     LEFT JOIN computadoras
        ON computadoras.id_laboratorio = laboratorios.id_laboratorio
     GROUP BY laboratorios.id_laboratorio, laboratorios.nombre
     ORDER BY laboratorios.nombre"
);

$error = $_GET["error"] ?? "";
$ok    = $_GET["ok"] ?? "";

$titulo = "Laboratorios";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Laboratorios</h1>
    <p>Lugares de la escuela donde hay computadoras.</p>
</div>

<?php if ($error === "tiene_computadoras") { ?>
    <div class="alerta alerta-error">
        No se puede eliminar un laboratorio que todavía tiene computadoras.
        Primero elimine o cambie de laboratorio a sus computadoras.
    </div>
<?php } ?>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">El laboratorio se creó correctamente.</div>
<?php } elseif ($ok === "editado") { ?>
    <div class="alerta alerta-exito">El laboratorio se actualizó correctamente.</div>
<?php } elseif ($ok === "eliminado") { ?>
    <div class="alerta alerta-exito">El laboratorio se eliminó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_laboratorio.php">+ Nuevo laboratorio</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Computadoras</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><strong><?php echo e($fila["nombre"]); ?></strong></td>
                <td><?php echo e($fila["cantidad_pc"]); ?></td>
                <td>
                    <div class="acciones">
                        <a class="btn btn-chico" href="editar_laboratorio.php?id=<?php echo e($fila["id_laboratorio"]); ?>">Editar</a>
                        <a class="btn btn-peligro btn-chico"
                           href="eliminar_laboratorio.php?id=<?php echo e($fila["id_laboratorio"]); ?>"
                           onclick="return confirm('¿Seguro que desea eliminar este laboratorio?');">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
