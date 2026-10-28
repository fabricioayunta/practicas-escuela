<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel administrativo";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["pi_nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="dashboard.php">
        <strong>Resumen</strong>
        <span>Cantidades de repuestos, préstamos de insumos y usuarios.</span>
    </a>

    <a class="opcion-grande" href="usuarios.php">
        <strong>Usuarios</strong>
        <span>Crear, editar o eliminar usuarios y sus módulos.</span>
    </a>

    <a class="opcion-grande" href="repuestos.php">
        <strong>Repuestos</strong>
        <span>Agregar, editar o eliminar repuestos y su stock.</span>
    </a>

    <a class="opcion-grande" href="prestamos.php">
        <strong>Préstamos de insumos</strong>
        <span>Ver todas las entregas, registrar nuevas y devoluciones.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
