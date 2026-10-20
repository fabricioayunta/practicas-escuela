<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel del profesor";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["panol_nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="articulos.php">
        <strong>Pedir un artículo</strong>
        <span>Ver qué hay disponible y registrar un préstamo a su nombre.</span>
    </a>

    <a class="opcion-grande" href="mis_prestamos.php">
        <strong>Mis préstamos</strong>
        <span>Ver los artículos que pidió y si ya fueron devueltos.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
