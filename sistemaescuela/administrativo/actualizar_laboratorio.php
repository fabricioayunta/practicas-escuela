<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_POST["id_laboratorio"];
$nombre = $_POST["nombre"];

$sql = "UPDATE laboratorios
        SET nombre='$nombre'
        WHERE id_laboratorio='$id'";

if(mysqli_query($conexion,$sql)){

    header("Location: laboratorios.php");
    exit();

}else{

    echo "Error al actualizar.";

}
?>