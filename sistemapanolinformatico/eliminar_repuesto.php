<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_GET["id"];

$sqlEliminar = "DELETE FROM repuestos
                WHERE id_repuesto='$id'";

mysqli_query($conexion,$sqlEliminar);

header("Location: repuestos.php");
exit();
?>
