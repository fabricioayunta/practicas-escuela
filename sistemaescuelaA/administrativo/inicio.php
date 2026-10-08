<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel administrativo";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="dashboard.php">
        <strong>Resumen</strong>
        <span>Cantidades de computadoras, tickets y usuarios.</span>
    </a>

    <a class="opcion-grande" href="usuarios.php">
        <strong>Usuarios</strong>
        <span>Crear, editar o eliminar usuarios.</span>
    </a>

    <a class="opcion-grande" href="laboratorios.php">
        <strong>Laboratorios</strong>
        <span>Administrar los laboratorios de la escuela.</span>
    </a>

    <a class="opcion-grande" href="computadoras.php">
        <strong>Computadoras</strong>
        <span>Agregar, editar o eliminar computadoras.</span>
    </a>

    <a class="opcion-grande" href="tickets.php">
        <strong>Tickets</strong>
        <span>Ver los avisos enviados por los profesores.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
