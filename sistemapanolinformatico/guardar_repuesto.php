<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$cantidad = $_POST["cantidad"];

$sql = "INSERT INTO repuestos (nombre, descripcion, cantidad)
        VALUES ('$nombre', '$descripcion', '$cantidad')";

if(mysqli_query($conexion,$sql)){

    header("Location: repuestos.php");
    exit();

}else{

    echo "Error al guardar el repuesto.";

}
?>
