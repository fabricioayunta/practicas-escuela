<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Panel del profesor";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Bienvenido/a, <?php echo e($_SESSION["nombre"]); ?></h1>
    <p>¿Qué desea hacer?</p>
</div>

<div class="grilla-menu">

    <a class="opcion-grande" href="crear_ticket.php">
        <strong>Crear un ticket</strong>
        <span>Avisar que una computadora tiene un problema.</span>
    </a>

    <a class="opcion-grande" href="mis_tickets.php">
        <strong>Mis tickets</strong>
        <span>Ver los avisos que envié y cómo van.</span>
    </a>

</div>

<?php include "../includes/pie.php"; ?>
