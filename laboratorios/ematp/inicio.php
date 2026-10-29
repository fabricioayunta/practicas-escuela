<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel EMATP";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="tickets.php">
        <strong>Gestionar tickets</strong>
        <span>Ver los avisos de los profesores y cambiar su estado.</span>
    </a>

    <a class="opcion-grande" href="computadoras.php">
        <strong>Computadoras</strong>
        <span>Ver y editar los componentes de cada computadora.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
