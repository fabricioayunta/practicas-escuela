<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

// borrar componentes primero (evita error FK)
mysqli_query($conexion,"DELETE FROM componentes WHERE id_computadora='$id'");

// borrar historial
mysqli_query($conexion,"DELETE FROM historial_computadoras WHERE id_computadora='$id'");

// borrar computadora
$sql = "DELETE FROM computadoras WHERE id_computadora='$id'";

if(mysqli_query($conexion,$sql)){
    header("Location: computadoras.php");
}else{
    echo "Error al eliminar";
}
?>