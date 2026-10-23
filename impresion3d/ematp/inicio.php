<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel EMATP";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["pi_nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="prestamos.php">
        <strong>Préstamos de insumos</strong>
        <span>Registrar entregas de insumos y sus devoluciones.</span>
    </a>

    <a class="opcion-grande" href="repuestos.php">
        <strong>Repuestos</strong>
        <span>Ver el stock y actualizar la cantidad cuando usa o recibe repuestos.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
