<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/relevamientos_vistas.php";

$titulo = "Mis relevamientos";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Mis relevamientos</h1>
    <p>Los controles de laboratorio que usted registró.</p>
</div>

<div class="barra-acciones">
    <a class="btn btn-exito" href="relevamiento.php">+ Nuevo relevamiento</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<?php rel_vista_listado($conexion, 2, (int)$_SESSION["id_usuario"]); ?>

<?php include "../includes/pie.php"; ?>
