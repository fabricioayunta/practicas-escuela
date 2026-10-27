<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/relevamientos_vistas.php";

$titulo = "Relevamientos de laboratorios";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Relevamientos de laboratorios</h1>
    <p>Controles de laboratorio registrados por los profesores. Los registros no se pueden modificar.</p>
</div>

<?php rel_vista_listado($conexion, 3); ?>

<?php include "../includes/pie.php"; ?>
