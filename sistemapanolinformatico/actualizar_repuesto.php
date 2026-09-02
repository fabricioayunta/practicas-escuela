<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_POST["id_repuesto"];
$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$cantidad = $_POST["cantidad"];

$sql = "UPDATE repuestos
        SET nombre='$nombre',
            descripcion='$descripcion',
            cantidad='$cantidad'
        WHERE id_repuesto='$id'";

if(mysqli_query($conexion,$sql)){

    header("Location: repuestos.php");
    exit();

}else{

    echo "Error al actualizar.";

}
?>
