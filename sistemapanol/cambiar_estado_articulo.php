<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_GET["id"];
$nuevo_estado = $_GET["estado"];

// Dar de alta o de baja el artículo
$sql = "UPDATE articulos
        SET estado='$nuevo_estado'
        WHERE id_articulo='$id'";

mysqli_query($conexion,$sql);

header("Location: articulos.php");
exit();
?>
