<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel EMATP";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["panol_nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="prestamos.php">
        <strong>Préstamos y devoluciones</strong>
        <span>Prestar artículos y registrar cuando los devuelven.</span>
    </a>

    <a class="opcion-grande" href="articulos.php">
        <strong>Artículos</strong>
        <span>Consultar los artículos y cuántos hay disponibles.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
