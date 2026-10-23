<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel del profesor";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["pi_nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="crear_prestamo.php">
        <strong>Pedir un insumo</strong>
        <span>Registrar un insumo que se lleva a su nombre.</span>
    </a>

    <a class="opcion-grande" href="mis_prestamos.php">
        <strong>Mis préstamos</strong>
        <span>Ver los insumos que pidió y si ya fueron devueltos.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
