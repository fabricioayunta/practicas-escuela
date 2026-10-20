<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel administrativo";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["panol_nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="dashboard.php">
        <strong>Resumen</strong>
        <span>Cantidades de artículos, préstamos y usuarios.</span>
    </a>

    <a class="opcion-grande" href="usuarios.php">
        <strong>Usuarios</strong>
        <span>Crear, editar o eliminar usuarios y sus módulos.</span>
    </a>

    <a class="opcion-grande" href="articulos.php">
        <strong>Artículos</strong>
        <span>Agregar, editar, dar de baja o eliminar artículos.</span>
    </a>

    <a class="opcion-grande" href="prestamos.php">
        <strong>Préstamos</strong>
        <span>Ver todos los préstamos, registrar nuevos y devoluciones.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
